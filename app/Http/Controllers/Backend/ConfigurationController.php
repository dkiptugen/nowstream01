<?php

    namespace App\Http\Controllers\Backend;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Log;
    use App\Traits\Meta;

    class ConfigurationController extends Controller
        {
            use Meta;
        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
         */
            public function index()
                {
                    $this->data['config'] = config('custom',[]);
                    return view('Backend.modules.configuration.index' ,$this->data);
                }

            public function edit(Request $request)
                {
                    foreach ($request->all() as $key => $value)
                        {
                            self::setEnv($key ,$value);
                        }
                    shell_exec('php ' . base_path('artisan') . ' config:clear');
                    return self::success('Configuration' ,'Added successfully' ,route('configuration.index'));
                }


        }
