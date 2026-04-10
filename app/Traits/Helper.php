<?php

    namespace App\Traits;


    use Illuminate\Support\Facades\Http;
	use Illuminate\Support\Facades\Storage;


    trait Helper
        {
	    /**
	     * @param $link
	     * @param $dt
	     * @param $token
	     * @param $method
	     *
	     * @return object|string|null
	     */
		    public function invoke_server($link, $dt, $token, $method = 'post')
                {
	                $headers = [
		                'Content-Type' => 'application/json'
	                ];
					//dd($headers);

	                $options = [
		                'verify' => app_path("Resources/cacert.pem"),
		                'http_errors' => false,
	                ];

	                switch (strtolower($method)) {
		                case 'post':
				                $response = Http::withHeaders($headers)
				                                ->withOptions($options)
					                            ->withToken($token)
				                                ->post($link, $dt);
				                break;
		                case 'put':
				                $response = Http::withHeaders($headers)
					                            ->withToken($token)
				                                ->withOptions($options)
				                                ->put($link, $dt);
				                break;
		                case 'get':
				                $response = Http::withHeaders($headers)
					                            ->withToken($token)
				                                ->withOptions($options)
				                                ->get($link, $dt);
				                break;
		                default:
				                throw new \InvalidArgumentException('Invalid HTTP method specified.');
		                }

	                if ($response->successful()) {
		                return $response->object();
	                }

	                return $response->object();
                }
		    public function invoke_server_2($link, $dt, $token, $method = 'post')
			    {
				    $ch = curl_init();

				    $headers = [
					    'Content-Type: application/json',
					    'Authorization: Bearer ' . $token,
				    ];

				    curl_setopt($ch, CURLOPT_URL, $link);
				    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
				    curl_setopt($ch, CURLOPT_CAINFO, app_path("Resources/cacert.pem"));

				    switch (strtolower($method)) {
					    case 'post':
							    curl_setopt($ch, CURLOPT_POST, true);
							    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dt));
							    break;
					    case 'put':
							    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
							    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dt));
							    break;
					    case 'get':
							    if (!empty($dt)) {
								    $link .= '?' . http_build_query($dt);
								    curl_setopt($ch, CURLOPT_URL, $link);
							    }
							    break;
					    default:
							    throw new \InvalidArgumentException('Invalid HTTP method specified.');
					    }

				    $response = curl_exec($ch);
				    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

				    if (curl_errno($ch)) {
					    throw new \Exception('cURL error: ' . curl_error($ch));
				    }

				    curl_close($ch);

				    if ($httpCode >= 200 && $httpCode < 300) {
					    return json_decode($response, false);
				    }

				    return $response;
			    }


	    /**
	     * @param  string  $msisdn
	     * @param          $prefix
	     * @param  int     $size
	     *
	     * @return string
	     */
		    public function msisdnFormatter(string $msisdn, $prefix = 254, int $size = 9)
            : string
                {
                    return $prefix . substr($msisdn, -($size));
                }

	    /**
	     * @param $thumbnail
	     * @param $class
	     * @param $style
	     *
	     * @return string
	     */
		    public function thumbnail_tag($thumbnail, $class, $style = "")
                {
                    $thumbnailUrl = $thumbnail
                        ? Storage::disk(config('filesystems.default'))->url($thumbnail)
                        : null;
                    return '<div class="w-100 h-100">
								<img src="' . $thumbnailUrl . '" class="' . $class . '" style="object-fit: cover; object-position: center; ' . $style . '" />
							</div>';
                }

	    /**
	     * @param $text
	     * @param $url
	     * @param $class
	     * @param $style
	     *
	     * @return string
	     */
		    public function anchor_link($text="", $url="", $class = "", $style = "")
                {
                    return '<div class="w-100 h-100"><a href="' . $url . '" class="' . $class . '" style="' . $style . '" >' . $text . '</a></div>';
                }

	    /**
	     * @param $actions
	     *
	     * @return string|null
	     */
		    public function tags($actions)
                {
                    $x = null;
                    foreach ($actions as $action):
                             $x.='<div class="badge badge-primary bg-dark m-1">' . htmlspecialchars($action) . '</div>';
                    endforeach;
                    return $x;
                }

	    /**
	     * @param $data
	     * @param $key
	     * @param $salt
	     *
	     * @return string
	     */
		    public function encrypt($data, $key, $salt)
			    {
				    $iv = substr(hash('sha256', $salt), 0, 16);
				    $encrypted = openssl_encrypt($data, 'AES-256-CBC', base64_decode($key), 0, $iv);
				    return base64_encode($encrypted);
			    }

		    public function decrypt($encryptedData, $key, $salt)
			    {
				    $iv = substr(hash('sha256', $salt), 0, 16);
				    $data = base64_decode($encryptedData);
				    return openssl_decrypt($data, 'AES-256-CBC', base64_decode($key), 0, $iv);
			    }
		    function isBazeEmail($email)
			    {
				    // Regular expression to match email addresses from baze.co.ke
				    $pattern = '/^[a-zA-Z0-9._%+-]+@live\.baze\.co\.ke$/';
				    return preg_match($pattern, $email) === 1;
			    }
        }
