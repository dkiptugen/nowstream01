<?php

namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Controller;
use App\Http\Datatables\SysUserDatatable;
use App\Http\Requests\EditUser;
use App\Http\Requests\UpdateProfile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
        public function index()
            {
                return view('Backend.modules.users.index', $this->data);
            }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response|\Illuminate\View\View|string
     */
        public function create()
            {

                $this->data['role'] = Role::get();
                return view('Backend.modules.users.add', $this->data);
            }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
        public function datatable(Request $request,SysUserDatatable $datatable)
            {

                $datatable->columns       = [ 1 => 'name', 2 => 'email', 3 => 'status'];
                return response()->json($datatable->data($request));
            }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreUser  $request
     *
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector|void
     */
        public function store(StoreUser $request)
            {

                $validateddata = $request->validated();
                if ($validateddata)
                    {
                        $user = User::where('email', strtolower($request->email))
                                    ->first();
                        if (is_null($user))
                            {
                                $user        = new User();
                                $user->email = strtolower(trim($request->email));
                                $user->name  = $request->name;
                                if ((isset($request->password) && !empty($request->password)) || (isset($request->con_password) && !empty($request->con_password)))
                                    {
                                        $valid = $request->validate([
                                                                        'password'     => ['required',
                                                                                           'string',
                                                                                           'min:' . env('PASSWORD_MINIMUM_LENGTH'),
                                                                                           'regex:' . env('PASSWORD_COMPLEXITY_REGEX'),
                                                                                           'same:con_password'],
                                                                        'con_password' => ['required']
                                                                    ]);
                                        if ($valid)
                                            {
                                                $user->password = bcrypt(trim($request->password));
                                            }
                                        else
                                            {
                                                return self::fail('User', $valid, route('user.index'));
                                            }
                                    }

                                $user->status  = $request->status ?? 0;
                                $user->type    = $request->user()->type;
                                $user->role_id = $request->role_id;
                                $usr           = $user->save();
                                if ($usr)
                                    {
                                        return self::success('User', 'Added user successfully', route('user.index'));
                                    }
                            }
                        else
                            {
                                if ((isset($request->password) && !empty($request->password)) || (isset($request->con_password) && !empty($request->con_password)))
                                    {
                                        $valid = $request->validate([
                                                                        'password'     => ['required',
                                                                                           'string',
                                                                                           'min:' . env('PASSWORD_MINIMUM_LENGTH'),
                                                                                           'regex:' . env('PASSWORD_COMPLEXITY_REGEX'),
                                                                                           'same:con_password'],
                                                                        'con_password' => ['required']
                                                                    ]);
                                        if ($valid)
                                            {
                                                $usr = $user->update(['password' => bcrypt(trim($request->password)), 'status' => 1, 'role_id' => $request->role, 'type' => $request->user()->type]);
                                            }
                                        else
                                            {
                                                return self::fail('User', $valid, route('user.index'));
                                            }

                                    }
                                else
                                    {
                                        $usr = $user->update(['status' => 1, 'role_id' => $request->role, 'type' => $request->user()->type]);
                                    }
                                if ($usr)
                                    {
                                        return self::success('User', 'Updated user successfully', route('user.index'));
                                    }
                            }


                        return self::fail('User', 'Failed to add user', route('user.index'));
                    }

                return self::fail('User', $validateddata, route('user.index'));

            }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\EditUser $request
     * @param int $id
     *
     * @return array|\Illuminate\Http\Response
     */
        public function update(EditUser $request, $id)
            {

                $validateddata = $request->validated();
                if ($validateddata)
                    {

                        $user        = User::find($id);
                        $user->email = strtolower($request->email);
                        $user->name  = $request->name;
                        //$user->can_notify = $request->notify;
                        if ((isset($request->password) && !empty($request->password)) || (isset($request->con_password) && !empty($request->con_password)))
                            {
                                $valid = $request->validate([
                                                                'password'     => ['required',
                                                                                   'string',
                                                                                   'min:' . env('PASSWORD_MINIMUM_LENGTH'),
                                                                                   'regex:' . env('PASSWORD_COMPLEXITY_REGEX'),
                                                                                   'same:con_password'],
                                                                'con_password' => ['required'],
                                                            ]);
                                if ($valid)
                                    {
                                        //Auth::logoutOtherDevices($request->password);
                                        $user->password = bcrypt(trim($request->password));
                                    }
                                else
                                    {
                                        return self::fail('User', $valid, route('user.index'));
                                    }
                            }
                        $user->{'type'} = $request->user()->type;
                        $user->status   = $request->status ?? 0;
                        $user->role_id  = $request->role;
                        $usr            = $user->save();
                        if ($usr)
                            {
                                return self::success('User', 'Updated user successfully', route('user.index'));
                            }

                        return self::fail('User', 'Failed to update user', route('user.index'));
                    }

                return self::fail('User', $validateddata, route('user.index'));
            }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
        public function show($id)
            {
            }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response|\Illuminate\View\View
     */
        public function edit($id)
            {

                $this->data['user'] = User::find($id);
                $this->data['role'] = Role::get();

                return view('modules.users.edit', $this->data);
            }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return array|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
        public function destroy($id)
            {
                try
                    {
                        $usr = User::whereId($id)->update(['type' => 'customer']);
                        if ($usr)
                            {
                                return self::success('User', 'User removed from organization', route('user.index'));
                            }

                        return self::fail('User', 'Failed to remove user from organization', route('user.index'));

                    }
                catch (Exception $e)
                    {
                        Log::error($e->getMessage());
                        return self::fail('User', 'encountered an error when removing the user', route('user.index'));
                    }

            }

    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
        public function export_view(): BinaryFileResponse
            {
                Log::info('method reached');
                return Excel::download(new UserExport(), 'users.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
        public function profile()
            {

                $this->data['user'] = Auth::user();

                return view('modules.users.profile', $this->data);
            }

    /**
     * @param \App\Http\Requests\UpdateProfile $request
     * @param $id
     * @return array|\Illuminate\Http\RedirectResponse
     */
        public function profile_update(UpdateProfile $request, $id)
            {

                $validateddata = $request->validated();
                if ($validateddata)
                    {
                        $user          = User::find($id);
                        $user->email   = strtolower($request->email);
                        $user->name    = $request->name;
                        $user->surname = $request->surname;
                        $user->phone   = $request->phone_number;

                        if ($request->hasAny(['password', 'password_confirmation']))
                            {
                                $request->validate([
                                                       'password'              => ['required',
                                                                                   'same:password_confirmation',
                                                                                   'string',
                                                                                   'min:' . env('PASSWORD_MINIMUM_LENGTH'),
                                                                                   'regex:' . env('PASSWORD_COMPLEXITY_REGEX')],
                                                       'password_confirmation' => ['required']
                                                   ]);
                                $user->password = bcrypt(trim($request->password));
                            }


                        $usr = $user->save();
                        if ($usr)
                            {
                                return self::success('User', 'Updated user successfully', route('user.index'));
                            }

                        return self::fail('User', 'Failed to update user', route('user.index'));
                    }
                else
                    {
                        return self::fail('profile', $validateddata, route('profile'));
                    }
            }

    /**
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return array|\Illuminate\Http\RedirectResponse
     */
        public function activate(Request $request, $id): array|RedirectResponse
            {

                $user         = User::find($id);
                $user->status = $request->status;
                $user->save();
                if ($user)
                    {
                        return self::success('User', 'Updated user status successfully', $request->location);
                    }
                return self::fail('User', 'Failed to update user status', $request->location);

            }
}
