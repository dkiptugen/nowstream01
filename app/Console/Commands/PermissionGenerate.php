<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Traits\Meta;
use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use  Spatie\Permission\Models\Permission;

class PermissionGenerate extends Command
    {
        use Meta;

        protected $signature   = 'permission:generate';
        protected $description = 'List registered models and their associated policies';

        public function handle()
            {
                $roles  = ['Super Admin'];
                foreach ($roles as $value)
                    {
                        $role = Role::updateOrCreate(['name'       => $value,
                                                      'guard_name' => 'admin'
                                                     ]);

                    }

                foreach (config('settings.permissions') as $key => $permissions)
                    {

                        foreach ($permissions as $permission)
                            {
                                $perm = Permission::updateOrCreate(['name' => $permission], [
                                    'name'             => $permission,
                                    'display_name'     => Str::replace('_', ' ', Str::title($permission)),
                                    'permission_group' => $key,
                                    "guard_name"=>'admin'
                                ]);
                                $role->givePermissionTo($perm);
                            }

                    };

                $this->info('Permissions created successfully');
            }
    }
