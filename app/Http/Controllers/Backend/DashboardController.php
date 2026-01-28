<?php

    namespace App\Http\Controllers\Backend;

    use App\Http\Controllers\Controller;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Cache;

    class DashboardController extends Controller
        {
            public function index()
                {
                    //dd(Auth::user()->active_channel);
                    $this->data['title'] = 'Dashboard : '. $this->data['title'];


                   // dd($this->data);
                    return view( 'Backend.modules.dashboard',$this->data );
                }
        }
