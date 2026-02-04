<?php

    namespace App\Traits;

    use App\Models\Category;
    use App\Models\Media;
    use App\Models\channel;
    use App\Models\Userchannel;
    use DateTime;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\File;
    use Illuminate\Support\Str;
    use Intervention\Image\Facades\Image;


    trait Meta
        {
        /**
         * @return array
         */
            public static function product_def()
            : array
                {
                    return [
                        'name' => 'Now Content',
                        'title' => 'Now Content',
                        'description' => 'Explore the limitless possibilities. ',
                        'logo' => asset('assets/nowstream-light.png'),
                        'image' => asset('assets/img/logo1.png'),
                        'keywords' => 'Now Content,',
                        'author' => 'Caydeesoft Solutions Limited'
                    ];

                }

		    function removeSpaces ($input)
			    {
				    $result = preg_replace('/[\s-]+/', '', $input);

				    return $result;
			    }
		    function maskPhoneNumber ($phoneNumber, $startVisible = 3, $endVisible = 3, $maskChar = '*')
			    {
				    $length = strlen ($phoneNumber);
				    if ($length <= ($startVisible + $endVisible))
					    {
						    // If the phone number is too short to mask, return it as is
						    return $phoneNumber;
					    }

				    $startPart = substr ($phoneNumber, 0, $startVisible);
				    $endPart   = substr ($phoneNumber, -$endVisible);

				    $maskedPart = str_repeat ($maskChar, $length - ($startVisible + $endVisible));

				    return $startPart.$maskedPart.$endPart;
			    }

		    public static function success($title, $message, string $redirecturl = "")
                {
                    return response()->json([
                                                'status' => true, 'msg' => $message, 'header' => $title, 'url' => $redirecturl
                                            ]);
                }

            public static function failed($title, $message, $redirecturl = "")
                {
                    return response()->json([
                                                'status' => false, 'msg' => $message, 'header' => $title, 'url' => $redirecturl
                                            ]);
                }

            public static function time_ago($datetime, $full = false)
                {
                    $now     = new DateTime;
                    $ago     = new DateTime($datetime);
                    $diff    = $now->diff($ago);
                    $diff->w = floor($diff->d / 7);
                    $diff->d -= $diff->w * 7;

                    $string = [
                        'y' => 'year', 'm' => 'month', 'w' => 'week', 'd' => 'day', 'h' => 'hour', 'i' => 'minute', 's' => 'second',
                    ];
                    foreach ($string as $k => &$v)
                        {
                            if ($diff->$k)
                                {
                                    $v = $diff->$k.' '.$v.($diff->$k > 1 ? 's' : '');
                                } else
                                {
                                    unset($string[$k]);
                                }
                        }

                    if (!$full)
                        {
                            $string = array_slice($string, 0, 1);
                        }
                    return $string ? implode(', ', $string).' ago' : 'just now';
                }

        /**
         * @param $type
         * @param $val
         *
         * @return void
         */

            public static function setEnv($key, $value)
                {
                    if ($key != '_token') {

                        $envFile = app()->environmentFilePath();
                        $currentEnv = file_get_contents($envFile);
                        $escapedKey = preg_quote($key, '/');

                        // Escape $ signs and other special characters in the value
                        $value = str_replace('$', '\\$', $value);
                        $value = addslashes($value);

                        // Check if the key already exists
                        if (preg_match("/^$escapedKey=/m", $currentEnv)) {
                            // If the key exists, update its value
                            $currentEnv = preg_replace("/^$escapedKey=.*$/m", "$key=\"$value\"", $currentEnv);
                        } else {
                            // If the key doesn't exist, add it to the end of the file
                            $currentEnv .= "\n$key=\"$value\"";
                        }

                        file_put_contents($envFile, $currentEnv);
                    }

                }


            public function get_class_name($model)
                {
                    if (preg_match('#([^\\\\]+)$#', $model, $matches))
                        {
                            return $matches[1];
                        }

                }
            public static function img_loc($img)
                {
                    $image = Cache::remember($img, 60, function () use($img){
                        $imgd = Image::make($img);
                        return base64_encode($imgd);
                    });

                    return response($image);

                }

            public static function image_display($logo, $class, $height)
                {
                    return '<img src="'.$logo.'" class="'.$class.'" height="'.$height.'" />';
                }

            public static function getMime($filePath)
                {

                    $finfo = finfo_open(FILEINFO_MIME_TYPE);

                    $mime = finfo_file($finfo, $filePath);

                    finfo_close($finfo);
                    return $mime;

                }

            public static function getFileSize($filePath)
                {
                    $fileSizeBytes = filesize($filePath);

                    if ($fileSizeBytes !== false)
                        {
                            // Output the file size in bytes
                            return $fileSizeBytes."b";
                        } else
                        {
                            // Unable to get file size information
                            return 0;
                        }


                }

            public static function estimateReadingTime($request)
                {
                    $content            = $request->input('content');
                    $wordsPerMinute     = 200;
                    $wordCount          = str_word_count(strip_tags($content));
                    $readingTimeMinutes = ceil($wordCount / $wordsPerMinute);
                    return response()->json([
                                                'word_count' => $wordCount, 'reading_time_minutes' => $readingTimeMinutes,
                                            ]);
                }

            public function active_channel()
                {
                    if (!Cache::has('channel'.Auth::user()->user_active_channel))
                        {
                            $channel = channel::whereIdentifier(Auth::user()->user_active_channel)->first();
                            if (!is_null($channel))
                                {
                                    Cache::put('channel'.Auth::user()->user_active_channel, $channel);
                                } else
                                {
                                    $userchannel               = SystemUserChannel::with([
                                                                                       'channel'
                                                                                   ])->where('system_user_id', Auth::user()->id)->first();
                                    $user                      = Auth::user();
                                    $user->user_active_channel = $userchannel->channel->identifier;
                                    $user->save();
                                    Cache::put('channel'.Auth::user()->user_active_channel, $userchannel->channel);
                                }

                        }
                    return Cache::get('channel'.Auth::user()->user_active_channel);
                }
            public function identifer($model, $column, $size = 8)
                {

                    $identifier = strtoupper(Str::random($size));
                    mark:
                    $model = '\\App\\Models\\' . $model;
                    $check = $model::where($column, $identifier)
                                   ->first();
                    if (!is_null($check))
                        {
                            $identifier = $identifier . ($check->id + 1);
                            goto mark;
                        }

                    return $identifier;
                }

		    function isBazeEmail($email)
			    {
				    // Regular expression to match email addresses from baze.co.ke
				    $pattern = '/^[a-zA-Z0-9._%+-]+@live\.baze\.co\.ke$/';
				    return preg_match($pattern, $email) === 1;
			    }
        }

