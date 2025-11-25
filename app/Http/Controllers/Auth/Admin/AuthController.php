<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use App\Traits\SocialLogin;
use Illuminate\Http\Request;

class AuthController extends Controller
    {
        use SocialLogin;
    }
