<?php

    namespace App\Http\Controllers\Auth\Admin;

    use App\Http\Controllers\Controller;
    use App\Models\Channel;
    use App\Traits\Meta;
    use Exception;
    use Illuminate\Contracts\Validation\Factory;
    use Illuminate\Foundation\Auth\AuthenticatesUsers;
    use Illuminate\Foundation\Precognition;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\Log;

    class LoginController extends Controller
        {
        /*
        |--------------------------------------------------------------------------
        | Login Controller
        |--------------------------------------------------------------------------
        |
        | This controller handles authenticating users for the application and
        | redirecting them to your home screen. The controller uses a trait
        | to conveniently provide its functionality to your applications.
        |
        */


            use AuthenticatesUsers;

        /**
         * Where to redirect users after login.
         *
         * @var string
         */
            protected $redirectTo = 'backend';

            use Meta;
            public $data = [];
            public function __construct()
                {
                    $this->data = self::product_def();
                }

            public function showLoginForm()
                {
                    return view('Backend.auth.login', $this->data);
                }

            protected function attemptLogin(Request $request)
                {
                    return $this->guard()->attempt(
                        $this->credentials($request), $request->boolean('remember')
                    );
                }

            protected function credentials(Request $request)
                {
                    $login      = $request->input('email');
                    $login_type = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
                    return $request->only($login_type, 'password', 'status');
                }
            protected function getValidationFactory()
                {
                    return app(Factory::class);
                }
            public function validate(Request $request, array $rules,
                                     array $messages = [], array $attributes = [])
                {
                    $validator = $this->getValidationFactory()->make(
                        $request->all(), $rules, $messages, $attributes
                    );

                    if ($request->isPrecognitive()) {
                        $validator->after(Precognition::afterValidationHook($request))
                                  ->setRules(
                                      $request->filterPrecognitiveRules($validator->getRulesWithoutPlaceholders())
                                  );
                    }

                    return $validator->validate();
                }

            public function validateLogin(Request $request)
                {
                    $login      = $request->input('email');
                    $login_type = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
                    $request->merge([$login_type => $login, 'status' => 1]);
                    if ($login_type === 'email')
                        {

                            $this->validate($request, [
                                'email'    => 'required|email',
                                'password' => 'required|min:5',
                            ]);
                            $this->username = $login_type;

                        }
                    else
                        {
                            unset($request->email);
                            $this->validate($request, [
                                'username' => 'required',
                                'password' => 'required|min:5',
                            ]);
                            $this->username = $login_type;
                        }
                }

            public function logout(Request $request)
                {
                    //dd();
                    try
                        {
                            $user                      = $this->guard()->user();
                            $user->microsite_id          = null;
                            $user->save();
                            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
                        }
                    catch (Exception $e)
                        {
                            Log::error($e->getMessage());
                        }
                    $this->guard()->logout();

                    $request->session()->invalidate();

                    $request->session()->regenerateToken();

                    if ($response = $this->loggedOut($request))
                        {
                            return $response;
                        }

                    return redirect().route('admin.login.form');
                }

            protected function guard()
                {
                    return Auth::guard('admin');
                }

            protected function authenticated(Request $request, $user)
                {



                }
        }
