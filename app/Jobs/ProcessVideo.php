<?php

    namespace App\Jobs;

    use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Foundation\Bus\Dispatchable;
    use Illuminate\Queue\InteractsWithQueue;
    use Illuminate\Queue\SerializesModels;
    use Illuminate\Support\Facades\Process;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    class ProcessVideo implements ShouldQueue
        {
            use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

            public $timeout = 7200; // Allow 2 hours for processing

            public function __construct(public string $sourcePath)
                {
                }

        /**
         * Handles the video processing workflow.
         *
         * This method processes a video file by performing the following tasks:
         * - Creates a local temporary directory for storing intermediate files.
         * - Downloads the video source file from the disk storage.
         * - Executes a transcoding command to generate HLS streams in multiple resolutions.
         * - Generates a video poster image file.
         * - Creates thumbnail images for the video at an interval.
         * - Uploads the processed files to the designated storage location.
         * - Cleans up temporary files and directories after processing.
         *
         * @return void
         * @throws \RuntimeException If the temporary output directory cannot be created.
         */
            public function handle()
            : void
                {
                    $disk       = Storage::disk(config('filesystems.default'));
                    $folderName = pathinfo($this->sourcePath, PATHINFO_FILENAME);
                    $tempBase   = storage_path("app/temp/{$folderName}");

                    if (!file_exists("{$tempBase}/out"))
                        {
                            if (!mkdir("{$tempBase}/out", 0777, true) && !is_dir("{$tempBase}/out"))
                                {
                                    throw new \RuntimeException(sprintf('Directory "%s" was not created', "{$tempBase}/out"));
                                }
                        }

                    $localSource = "{$tempBase}/source.mp4";
                    file_put_contents($localSource, $disk->get($this->sourcePath));

                    $transcodeCmd = "ffmpeg -i {$localSource} " .
                                    "-filter_complex \"[0:v]split=3[v1][v2][v3]; " .
                                    "[v1]scale=360:640:force_original_aspect_ratio=decrease,pad=360:640:(ow-iw)/2:(oh-ih)/2[v360]; " .
                                    "[v2]scale=1280:720:force_original_aspect_ratio=decrease,pad=1280:720:(ow-iw)/2:(oh-ih)/2[v720]; " .
                                    "[v3]scale=1820:1024:force_original_aspect_ratio=decrease,pad=1820:1024:(ow-iw)/2:(oh-ih)/2[v1024]\" " .
                                    "-map \"[v360]\" -map 0:a -b:v:0 800k -map \"[v720]\" -map 0:a -b:v:1 2800k -map \"[v1024]\" -map 0:a -b:v:2 5000k " .
                                    "-c:a aac -b:a 128k -f hls -hls_time 6 -hls_playlist_type vod -master_pl_name master.m3u8 " .
                                    "-var_stream_map \"v:0,a:0 v:1,a:1 v:2,a:2\" {$tempBase}/out/v%v.m3u8";

                    Process::timeout($this->timeout)->run($transcodeCmd);

                    $posterPath = "{$tempBase}/out/poster.jpg";
                    $posterCmd  = "ffmpeg -ss 00:00:05 -i {$localSource} -vframes 1 -q:v 2 {$posterPath}";
                    Process::run($posterCmd);


                    $gridCmd = "ffmpeg -i {$localSource} -vf \"fps=1/10,scale=320:-1\" {$tempBase}/out/thumb_%03d.jpg";
                    Process::run($gridCmd);

                    $allGeneratedFiles = glob("{$tempBase}/out/*");
                    foreach ($allGeneratedFiles as $file)
                        {
                            $disk->put(
                                "processed/{$folderName}/" . basename($file),
                                fopen($file, 'r+')
                            );
                        }

                    $this->cleanup($tempBase);
                }

        /**
         * Recursively removes all files and directories within the specified path,
         * then deletes the directory itself.
         *
         * @param string $path The path to be cleaned up.
         */
            protected function cleanup($path)
                {
                    if (!is_dir($path)) return;
                    array_map(fn($f) => is_dir($f) ? $this->cleanup($f) : unlink($f), glob("$path/*"));
                    rmdir($path);
                }
        }
