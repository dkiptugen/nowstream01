<?php

    namespace App\Http\Controllers\Backend;

    use App\Http\Controllers\Controller;
    use App\Traits\Meta;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Cache;

    class DashboardController extends Controller
        {
            use Meta;
            public $data = [];
            public function __construct()
                {
                    $this->data = self::product_def();
                }
            public function index()
                {
                    //dd(Auth::user()->active_channel);
                    $this->data['title'] = 'Dashboard : '. $this->data['title'];


                   // dd($this->data);
                    return view( 'Backend.modules.dashboard',$this->data );
                }
        }
