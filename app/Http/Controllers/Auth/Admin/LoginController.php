<?php

    namespace App\Http\Controllers\Auth\Admin;

    use App\Http\Controllers\Controller;
    use App\Models\Channel;
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

        /**
         * Create a new controller instance.
         *
         * @return void
         */
            public function __construct()
                {
                    parent::__construct();

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
                    if ($login_type == 'email')
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
                            $user->user_active_channel = null;
                            $user->save();
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

                    return $request->wantsJson()
                        ? new JsonResponse([], 204)
                        : redirect('/');
                }

            protected function guard()
                {
                    return Auth::guard('admin');
                }

            protected function authenticated(Request $request, $user)
                {

                    if ($user->type == 'owner')
                        {
                            Cache::put('user_channels_' . $user->id, Channel::orderBy('created_at', 'desc')->limit(10)->get());
                        }
                    else
                        {
                            if (!is_null($user->channels))
                                {
                                    Cache::put('user_channels_' . $user->id, $user->channels);
                                }
                        }


                }
        }
