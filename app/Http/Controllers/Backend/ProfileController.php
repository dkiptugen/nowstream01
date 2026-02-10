<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SystemUser;
use App\Traits\Meta;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
        use Meta;
        public $data = [];
        public function __construct()
            {
                $this->data = self::product_def();
            }
    public function index(SystemUser $user)
        {
            $this->data['user']     =   $user;
            $this->data['option']   =   false;
            if(!is_null($user->meta))
                {
                    $this->data['option']   =   $user->meta->mapToGroups(function ($item, $key) {
                                                                    return [$item['meta_key'] => $item['meta_value']];
                                                                })->all();
                }


            return view('Backend.modules.profile.index',$this->data);

        }
    public function update(User $user, Request $request)
        {

        }
}
