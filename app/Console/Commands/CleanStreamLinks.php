<?php

namespace App\Console\Commands;

use App\Models\Content;
use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Throwable;

class CleanStreamLinks extends Command
    {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
        protected $signature = 'streams:clean
        {--group=livestream,tv,radio : Comma-separated content groups to scan}
        {--limit=0 : Maximum number of rows to scan}
        {--timeout=15 : HTTP timeout in seconds}
        {--include-disabled : Also scan records already marked disabled}
        {--dry-run : Show what would change without updating the database}';

    /**
     * The console command description.
     *
     * @var string
     */
        protected $description = 'Check stream links and set content status to 2 when the upstream link is not accessible.';

        public function handle(): int
            {
                $groups          = collect(explode(',', (string) $this->option('group')))
                    ->map(fn ($group) => trim($group))
                    ->filter()
                    ->values();
                $limit           = max((int)$this->option('limit'), 0);
                $timeout         = max((int)$this->option('timeout'), 1);
                $dryRun          = (bool)$this->option('dry-run');
                $includeDisabled = (bool)$this->option('include-disabled');

                $query = Content::query()
                                ->whereIn('content_group', $groups->all())
                                ->where(function ($builder)
                                    {
                                        $builder
                                            ->whereNotNull('stream_video_link')
                                            ->orWhereNotNull('stream_url');
                                    })
                                ->orderBy('created_at');

                if (!$includeDisabled)
                    {
                        $query->where('status', '!=', 2);
                    }

                if ($limit > 0)
                    {
                        $query->limit($limit);
                    }

                $contents = $query->get();

                if ($contents->isEmpty())
                    {
                        $this->warn('No content found for content_group(s) [' . $groups->join(', ') . '].');
                        return self::SUCCESS;
                    }

                $this->info("Scanning {$contents->count()} content item(s) in group(s) [" . $groups->join(', ') . ']...');

                $checked  = 0;
                $disabled = 0;
                $healthy  = 0;
                $skipped  = 0;

                $this->withProgressBar($contents, function (Content $content) use ($timeout, $dryRun, &$checked, &$disabled, &$healthy, &$skipped): void
                    {
                        $checked++;

                        $url = $this->resolveStreamUrl($content);

                        if ($url === null)
                            {
                                $reason = 'Missing stream URL.';
                                $this->disableContent($content, $reason, $dryRun);
                                $disabled++;
                                return;
                            }

                        [$isAccessible, $reason] = $this->checkUrl($url, $timeout);

                        if ($isAccessible)
                            {
                                $healthy++;
                                return;
                            }

                        if ($content->status === 2 && $content->disable_reason === $reason)
                            {
                                $skipped++;
                                return;
                            }

                        $this->disableContent($content, $reason, $dryRun);
                        $disabled++;
                    });

                $this->newLine(2);
                $this->table(
                    ['Metric', 'Count'],
                    [
                        ['Checked', $checked],
                        ['Disabled', $disabled],
                        ['Healthy', $healthy],
                        ['Skipped', $skipped],
                        ['Dry run', $dryRun ? 'yes' : 'no'],
                    ]
                );

                return self::SUCCESS;
            }

        private function resolveStreamUrl(Content $content): ?string
            {
                $url = trim((string)($content->stream_video_link ?: $content->stream_url));

                if ($url === '')
                    {
                        return null;
                    }

                if (!filter_var($url, FILTER_VALIDATE_URL)) {
                    return null;
                }

                return $this->normalizeUpstreamUrl($url);
            }

    /**
     * @return array{0: bool, 1: string|null}
     */
        private function checkUrl(string $url, int $timeout): array
            {
                try
                    {
                        $headResponse = $this->makeRequest('head', $url, $timeout);

                        if ($this->responseLooksHealthy($headResponse, $url, false))
                            {
                                return [true, null];
                            }

                        $getResponse = $this->makeRequest('get', $url, $timeout);

                        if ($this->responseLooksHealthy($getResponse, $url, true))
                            {
                                return [true, null];
                            }

                        $status = $getResponse?->status() ?? $headResponse?->status() ?? 0;
                        $reason = $status > 0
                            ? "Upstream returned HTTP {$status}."
                            : 'Upstream did not return a valid response.';

                        return [false, $reason];
                    }
                catch (ConnectionException $exception)
                    {
                        return [false, 'Connection error: ' . $exception->getMessage()];
                    }
                catch (Throwable $exception)
                    {
                        return [false, 'Validation error: ' . $exception->getMessage()];
                    }
            }

        private function makeRequest(string $method, string $url, int $timeout): ?Response
            {
                return Http::timeout($timeout)
                           ->connectTimeout(min($timeout, 10))
                           ->retry(1, 300)
                           ->withoutVerifying()
                           ->accept('application/vnd.apple.mpegurl, application/x-mpegURL, audio/*, video/*, */*')
                           ->withHeaders([
                               'User-Agent' => 'NowStream Stream Cleaner/1.0',
                               'Range'      => 'bytes=0-0',
                           ])
                           ->send(strtoupper($method), $url);
            }

        private function normalizeUpstreamUrl(string $url): string
            {
                $parts = parse_url($url);

                if (!$parts || empty($parts['scheme']) || strtolower($parts['scheme']) !== 'http') {
                    return $url;
                }

                $port = (int) ($parts['port'] ?? 80);

                if (!in_array($port, [80, 443], true)) {
                    return $url;
                }

                $httpsUrl = preg_replace('/^http:/i', 'https:', $url, 1);

                return $httpsUrl ?? $url;
            }

        private function responseLooksHealthy(?Response $response, string $url, bool $inspectBody): bool
            {
                if (!$response)
                    {
                        return false;
                    }

                if (!$response->successful() && $response->status() !== 206)
                    {
                        return false;
                    }

                $contentType = strtolower((string)$response->header('Content-Type'));
                $isPlaylist  = str_contains(strtolower($url), '.m3u8') || str_contains($contentType, 'mpegurl');

                if (!$inspectBody || !$isPlaylist)
                    {
                        return true;
                    }

                $body = trim((string)$response->body());

                return $body === '' || str_contains($body, '#EXTM3U');
            }

        private function disableContent(Content $content, string $reason, bool $dryRun): void
            {
                $message = sprintf(
                    '[%s] %s -> status=2 (%s)',
                    $content->uuid,
                    $content->title,
                    $reason
                );

                if ($dryRun)
                    {
                        $this->line($message);
                        return;
                    }

                $content->forceFill([
                    'status'         => 2,
                    'disable_reason' => $reason,
                ])->save();

                $this->line($message);
            }
    }
