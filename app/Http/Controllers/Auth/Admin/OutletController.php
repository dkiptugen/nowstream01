<?php

    namespace App\Http\Controllers\Auth\Admin;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\CreateChannel;
    use App\Http\Services\UploadService;
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

            public function choose_channel(Request $request)
                {
                    $this->data['product'] = SystemUserChannel::with([
                                                                         'channel'
                                                                     ])->where('system_user_id', $request->user()->id)->get();

                    //dd( $this->data['product']);

                    return view('Backend.auth.choose_channel', $this->data);
                }

            public function select_channel(Request $request)
                {

                    $validated = $request->validate([
                                                        'channel' => ['string', 'required']
                                                    ]);

                    if ($validated)
                        {
                            $user             = Auth::user();
                            $user->channel_id = $request->channel;
                            $user->save();
                            return redirect()->route('backend.admin_dashboard');
                        }

                }

            public function channel_change(Channel $channel)
                {
                    $user = Auth::guard('admin')->user();
                    $user->channel_id = $channel->uuid;
                    $sav              = $user->save();
                    if ($sav)
                        {
                            return redirect()->route('backend.admin_dashboard');
                        }
                }
            public function create_channel_view()
                {
                    return view('Backend.auth.create_channel',$this->data);
                }
            public function store_channel(CreateChannel $request)
                {
                    $validateddata = $request->validated();
                    if($validateddata)
                        {
                            $channel             = new Channel();
                            $channel->name       = $validateddata['channel_name'];
                            if ($request->hasFile('thumbnail'))
                                {
                                    $image              = new UploadService();
                                    $upload             = $image->file_upload($request, 'thumbnail', 'channel_thumbnail');
                                    $channel->thumbnail = $upload['path'];

                                }
                            if ($request->hasFile('cover_image'))
                                {
                                    $image                = new UploadService();
                                    $upload               = $image->file_upload($request, 'cover_image', 'channel_cover');
                                    $channel->cover_image = $upload['path'];

                                }
                            $channel->description       = $validateddata['channel_description'];
                            $channel->status            = 1;
                            $res                        = $channel->save();
                            if ($res)
                                {
                                    $user = $request->user();
                                    $user->channel_id = $channel->id;
                                    $user->save();
                                    return self::success('channel', 'Saved successfully', route('backend.admin_dashboard'));
                                }
                            return self::failed('channel', 'error encountered when saving, try again later', route('backend.admin_dashboard'));

                        }
                    else
                        {
                            return self::failed('channel', $validateddata, route('backend.admin_dashboard'));
                        }
                }
        }
