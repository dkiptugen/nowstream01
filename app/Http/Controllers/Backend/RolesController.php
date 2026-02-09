<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Datatables\RoleDatatable;
use App\Http\Requests\StoreRole;
use App\Http\Requests\UpdateRole;
use App\Models\Permission;
use App\Models\Role;
use App\Traits\Meta;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RolesController extends Controller
    {


        public function __construct()
            {
                parent::__construct();
            }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response|\Illuminate\View\View|string
     */
        public function index()
            {

                return view('Backend.modules.roles.index', $this->data);
            }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response|\Illuminate\View\View|string
     */
        public function create()
            {

                return view('Backend.modules.roles.add', $this->data);
            }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Http\Response
     */
        public function store(StoreRole $request)
            {
                $validateddata = $request->validated();
                if ($validateddata)
                    {
                        $role       = new Role();
                        $role->name = $request->role;
                        $req        = $role->save();
                        if ($req)
                            {
                                if (isset($request->perm))
                                    {

                                        foreach ($request->perm as $value)
                                            {
                                                $pr                = new PermissionRole();
                                                $pr->role_id       = $role->id;
                                                $pr->permission_id = $value;
                                                $pr->save();
                                            }
                                    }

                                return self::success('Role', 'Success', route('user.roles.index', 0));
                            }

                        return self::failed('Role', 'Fail', route('user.roles.index', 0));

                    }

                return self::failed('Role', $validateddata, route('user.roles.index', 0));

            }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response|\Illuminate\View\View|string
     */
        public function show($id)
            {
                $this->data['role'] = Role::find($id);
                return view('Backend.modules.roles.view', $this->data);
            }

        public function assign_view($id)
            {
                $this->data['role'] = Role::find($id);

                $permissions = Permission::get();
                foreach ($permissions as $permission)
                    {
                        $this->data['permission'][$permission->permission_group][] = ['id'   => $permission->id,
                                                                                      'name' => $permission->name, 'display_name' => $permission->display_name];
                    }

                //dd( $this->data['permission']);
                return view('Backend.modules.roles.assign', $this->data);
            }

        public function assign(Request $request, $id)
            {
                $role = Role::find($id);
                $role->syncPermissions([]);
                foreach ($request->perm as $permission)
                    {
                        $role->givePermissionTo($permission);
                    }
                return self::success('Role Assignment', 'Successful', route('backend.role.index'));
            }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response|\Illuminate\View\View
     */
        public function edit($id)
            {
                $this->data['role'] = Role::find($id);

                return view('Backend.modules.roles.edit', $this->data);
            }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return array|\Illuminate\Http\Response
     */
        public function update(UpdateRole $request, $id)
            {

                $validateddata = $request->validated();
                if ($validateddata)
                    {
                        $role       = Role::find($id);
                        $role->name = $request->role;
                        $req        = $role->save();
                        if ($req)
                            {
                                if (isset($request->perm))
                                    {
                                        Permission_Role::where('role_id', $id)->delete();
                                        foreach ($request->perm as $value)
                                            {
                                                $pr                = new Permission_Role();
                                                $pr->role_id       = $id;
                                                $pr->permission_id = $value;
                                                $pr->save();
                                            }
                                    }

                                return self::success('Role', 'Success', route('user.roles.index', 0));
                            }
                        return self::failed('Role', 'Failed', route('user.roles.index', 0));
                    }
                return self::failed('Role', $validateddata, route('user.roles.index', 0));
            }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
        public function destroy($userid, $id)
            {
                //
            }

        public function datatable(Request $request, RoleDatatable $datatable)
            {
                $datatable->columns = [1 => 'name'];
                // Custom method logic
                return response()->json($datatable->data($request));
            }

    }
