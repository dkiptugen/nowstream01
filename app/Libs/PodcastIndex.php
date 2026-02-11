<?php

	namespace App\Libs;

	use Illuminate\Support\Facades\Http;
	use PodcastIndex\Client;

	ini_set('memory_limit', '-1');

	class PodcastIndex
		{
			public    $timestamp;
			public    $apikey    = '3Y5SHWESUFY9JETAGNTD';
			public    $apisecret = 'xLUQXt4ksdXzNHCJJ4wSzL^K#ZH2aVw8BM7H4Dvc';
			protected $headers;
			protected $hash;

			public function __construct()
				{
					$timestamp     = time();
					$this->hash    = sha1($this->apikey.$this->apisecret.$timestamp);
					$this->headers = [
						"User-Agent"    => "RAD/1.3",
						"X-Auth-Key"    => $this->apikey,
						"X-Auth-Date"   => $timestamp,
						"Authorization" => $this->hash,
						"accept"        => "application/json"
					];
				}

			public function podcastCategories()
				{
					$data = Http::withHeaders($this->headers)->get('https://api.podcastindex.org/api/1.0/categories/list?pretty');
					if ($data->successful())
						{
							return $data->object();
						}
					return $data->reason();
				}

			public function trending_podcast($cat)
				{

					$data = Http::withHeaders($this->headers)->get('https://api.podcastindex.org/api/1.0/podcasts/trending', [
						'cat' => $cat,
						"max" => 1000
					]);
					if ($data->successful())
						{
							//dd($data->object());
							return $data->object();
						}

				}

			public function episodes($podcastId, $size = 20)
				{
					$data = Http::withHeaders($this->headers)->get('https://api.podcastindex.org/api/1.0/episodes/byfeedid', [
						'id'  => $podcastId,
						'max' => $size
					]);
					if ($data->successful())
						{
							return $data->object();
						}
				}

			public function podcast_search($title)
				{
					$data = Http::withHeaders($this->headers)->get('https://api.podcastindex.org/api/1.0/search/byterm', ['q' => $title]);
					if ($data->successful())
						{
							return $data->object();
						}
				}

			public function podcast_by_id($podcastId)
				{

					$data = Http::withHeaders($this->headers)->get('https://api.podcastindex.org/api/1.0/podcasts/byfeedid', ['id' => $podcastId]);
					if ($data->successful())
						{
							return $data->object();
						}
				}

			public function podcast_by_guid($podcastId)
				{

					$data = Http::withHeaders($this->headers)->get('https://api.podcastindex.org/api/1.0/podcasts/byguid', ['guid' => $podcastId]);
					if ($data->successful())
						{
							return $data->object();
						}
				}
		}
