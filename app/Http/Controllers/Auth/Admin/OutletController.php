<?php

    namespace App\Http\Controllers\Auth\Admin;

    use App\Http\Controllers\Controller;
    use App\Models\Channel;
    use App\Models\SystemUserChannel;
    use App\Models\UserProduct;
    use App\Traits\Meta;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Cache;

    class OutletController extends Controller
        {
            use Meta;
            public $data = [];
            public function __construct()
                {
                    $this->data = self::product_def();
                }
            public function selectOutlet(Request $request)
                {
                    $this->data['product'] = SystemUserChannel::with([
                                                                   'channel'
                                                               ])->where('system_user_id', $request->user()->id)->get();

                    //dd( $this->data['product']);

                    return view('Backend.auth.outlet', $this->data);
                }

            public function saveOutlet(Request $request)
                {
                    $channel                   = Channel::find($request->channel);
					if(!is_null($channel))
						{
							$user                      = Auth::user();
							$user->user_active_channel = $channel->identifier;
							$user->save();

							return redirect()->route('admin_dashboard');
						}

                }

            public function outlet_change($identifier)
                {
                    $user                      = Auth::guard('admin')->user();
                    if ($user->type == 'owner')
                        {
                            $channel = Channel::where('identifier', $identifier)
                                              ->first();
                        }
                    else
                        {
                            $syschannel = SystemUserChannel::with(['channel'])
                                                        ->where('system_user_id', $user->id)
                                                        ->whereHas('channel', function ($query) use ($identifier) {
                                                            $query->where('identifier', $identifier);
                                                        })
                                                        ->first();
                            if(!is_null($syschannel))
                                {
                                    $channel = $syschannel->channel;
                                }

                        }

                    //dd($channel);

                    if (!is_null($channel))
                        {

                            $user->user_active_channel = $channel->identifier;
                            $sav =$user->save();
                            if($sav)
                                {
                                    Cache::delete('user_channels_'.$user->id);

                                    if ($user->type == 'owner')
                                        {
                                            Cache::put('user_channels_' . $user->id, Channel::where('identifier','<>',$user->user_active_channel)->orderBy('created_at', 'desc')->limit(10)->get());
                                        }
                                    else
                                        {
                                            if (!is_null($user->channels))
                                                {
                                                    Cache::put('user_channels_' . $user->id, $user->channels->filter(function ($item) use ($user) {
                                                        return $item['identifier'] !== $user->user_active_channel;
                                                    }));
                                                }
                                        }
                                }
                        }
                    else
                        {
                            Cache::delete('user_channels_'.$user->id);

                            if ($user->type == 'owner')
                                {
                                    Cache::put('user_channels_' . $user->id, Channel::where('identifier','<>',$user->user_active_channel)->orderBy('created_at', 'desc')->limit(10)->get());
                                    $user->user_active_channel = Cache::get('user_channels_' . $user->id)->first()->identifier;
                                    $user->save();
                                }
                            else
                                {
                                    if (!is_null($user->channels))
                                        {
                                            $user->user_active_channel = $user->channels->first()->identifier;
                                            $user->save();
                                            Cache::put('user_channels_' . $user->id, $user->channels->filter(function ($item) use ($user) {
                                                return $item['identifier'] !== $user->user_active_channel;
                                            }));
                                        }
                                }

                        }


                    return redirect()->route('admin_dashboard');
                }
        }
