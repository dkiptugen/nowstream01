<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use App\Traits\Meta;
use App\Traits\SocialLogin;
use Illuminate\Http\Request;

class AuthController extends Controller
    {
        use SocialLogin;
        use Meta;
        public $data = [];
        public function __construct()
            {
                $this->data = self::product_def();
            }
    }
