<?php

    namespace App\Http\Controllers\Auth\Admin;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\CreateChannel;
    use App\Http\Requests\StoreMicrosite;
    use App\Http\Services\UploadService;
    use App\Models\Channel;
    use App\Models\Microsite;
    use App\Models\Role;
    use App\Models\SystemUserChannel;
    use App\Models\SystemUserMicrosite;
    use App\Models\UserProduct;
    use App\Traits\Meta;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\DB;

    class OutletController extends Controller
        {
            use Meta;

            public $data = [];

            public function __construct()
                {
                    $this->data = self::product_def();
                }

            public function choose_brand(Request $request)
                {
                    $this->data['product'] = SystemUserMicrosite::with([
                                                                         'microsite'
                                                                     ])->where('system_user_id', $request->user()->id)->get();

                    //dd( $this->data['product']);

                    return view('Backend.auth.choose_channel', $this->data);
                }

            public function select_brand(Request $request)
                {

                    $validated = $request->validate([
                                                        'microsite' => ['string', 'required']
                                                    ]);

                    if ($validated)
                        {
                            $user             = Auth::user();
                            $user->microsite_id = $request->microsite;
                            $user->save();
                            return redirect()->route('backend.admin_dashboard');
                        }

                }

            public function brand_change(Microsite $microsite)
                {
                    $user = Auth::guard('admin')->user();
                    $user->microsite_id = $microsite->uuid;
                    $sav              = $user->save();
                    if ($sav)
                        {
                            return redirect()->route('backend.admin_dashboard');
                        }
                }
            public function create_brand_view()
                {
                    return view('Backend.auth.create_channel',$this->data);
                }
            public function store_brand(StoreMicrosite $request)
                {

                    $validated = $request->validated();
                    $validated['verified'] = 0;
                    $validated['visible'] = 0;
                    //DB::beginTransaction();
                    try
                        {
                            $microsite = new Microsite();
                            $result    = $microsite->create($validated);

                            if ($result)
                                {

                                    $user = Auth::guard('admin')->user();

                                    if(!$user->hasRole('Super Admin'))
                                        {

                                            $role = Role::firstOrCreate(
                                                ['name' => 'ContentOwner', 'guard_name' => 'admin']
                                            );
                                            $result->users()->attach($user->id, [
                                                'role_id' => $role->id
                                            ]);
                                            $user->assignRole('ContentOwner');

                                        }
                                    else
                                        {
                                            $result->users()->attach($user->id, [
                                                'role_id' => 1
                                            ]);
                                        }

                                    $user->microsite_id = $result->uuid;
                                    $user->save();
                                    //dd($user);
                                    //DB::commit();
                                    return self::success(
                                        'Microsite',
                                        'Store successful',
                                        route('backend.admin_dashboard')
                                    );
                                }

                            return self::failed(
                                'Microsite',
                                'Store failed',
                                route('backend.microsite.index')
                            );

                        }
                    catch (\Throwable $e)
                        {
                            \Log::error('Microsite store error: ' . $e->getMessage());

                            return self::failed(
                                'Microsite',
                                'Something went wrong',
                                route('backend.microsite.index')
                            );
                        }
                }
        }
