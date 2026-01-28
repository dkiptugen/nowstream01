<?php

	namespace App\Http\Controllers\Auth\User;

	use App\Events\LogoutUser;
	use App\Libs\AfricasTalking;
	use App\Models\User;
	use App\Rules\NotExecutable;
	use App\Rules\ValidatePhone;
    use App\Traits\SocialLogin;
    use Illuminate\Http\Request;
	use App\Http\Controllers\Controller;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\Cache;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Hash;
	use Illuminate\Auth\Events\Registered;
	use Illuminate\Database\QueryException;
	use Illuminate\Support\Facades\Log;
	use Illuminate\Support\Facades\Password;
	use Illuminate\Support\Facades\Session;
	use Illuminate\Support\Facades\Validator;
	use Illuminate\Foundation\Auth\EmailVerificationRequest;
	use App\Http\Services\UploadService;
	use Illuminate\Support\Str;
	use function PHPUnit\Framework\isNull;

	class AuthsController extends Controller
		{
            use SocialLogin;
			public function showRegisterForm ()
				{
					return view ('Frontend.auth.register');
				}

			public function partner ()
				{
					return view ('Frontend.auth.partner');
				}

			public function register (Request $request)
				{
					$validator = Validator::make ($request->all (), [
						'name' => 'required|string|max:255', 'email' => 'required|string|email|max:255|unique:users', 'password' => 'required|string|min:8|confirmed', 'phone' => 'nullable|string|max:15|unique:users', 'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048', new NotExecutable()],
					]);

					if ($validator->fails ())
						{
							return back ()->withErrors ($validator)->withInput ();
						}

					$uploadPath = 'assets/uploads/avatars/default.png'; // Default avatar path

					if ($request->hasFile ('image'))
						{
							$image      = new UploadService();
							$upload     = $image->file_upload ($request, 'image', 'image', 'public_2');
							$uploadPath = $upload['path'];
						}
					else
						{
							// Extract the first letter of the user's name
							$firstLetter = strtoupper (substr ($request->name, 0, 1));
							$uploadPath  = "assets/uploads/avatars/{$firstLetter}.png";
						}

					try
						{
							$user = User::create ([
								                      'name' => $request->name, 'email' => $request->email, 'password' => Hash::make ($request->password), 'phone' => $request->phone, 'image' => $uploadPath,
							                      ]);

							Auth::login ($user);
							return redirect ()->intended ('/')->with ('success',
							                                          'Registration successful. Welcome back!');
						}
					catch (QueryException $e)
						{
							if ($e->getCode () == 23000)
								{ // Integrity constraint violation
									return redirect ()->back ()->withErrors (['phone' => 'The phone number has already been taken.'])->withInput ();
								}
							return redirect ()->back ()->withErrors (['error' => 'An error occurred while processing your request.'])->withInput ();
						}
				}


			public function login (Request $request)
				{
					$credentials = $request->validate ([
						                                   'email' => 'required|email', 'password' => 'required',
					                                   ]);
					if (Auth::attempt ($credentials))
						{
							$user = Auth::user ();
							if (!is_null ($user->stream_auth) && $user->stream_auth !== session ()->getId ())
								{
									Session::getHandler ()->destroy ($user->stream_auth);
								}
							$user->stream_auth = session ()->getId ();
							$user->save ();

							$request->session ()->regenerate ();
							return redirect ()->intended ('/')->with ('success', 'Login successful. Welcome back!');
						}

					return back ()->withErrors ([
						                            'email' => 'The provided credentials do not match our records.',
					                            ])->withInput ();
				}


			public function showPhoneLoginForm ()
				{
					return view ('Frontend.auth.phone_login');
				}

			public function showLoginForm (Request $request)
				{
					$country = $request->country;

					/*if ($country == 'KE')
						{
							return view ('Frontend.auth.phone_login');
						}*/
					return view ('Frontend.auth.login');
				}


			function generateOTP ($length = 6)
				{
					$otp = '';
					for ($i = 0; $i < $length; $i++)
						{
							$otp .= mt_rand (0, 9);
						}
					return $otp;
				}


			public function phoneLogin (Request $request)
				{
					$request->validate ([
						                    'phone' => ['required', 'string', 'max:15', new ValidatePhone()]
					                    ]);

					$phone = $this->removeSpaces ("254".substr ($request->input ('phone'), -9));

					$user = User::where ('phone', $phone)->first ();

					if (is_null ($user))
						{
							// User does not exist, create a new account
							$lastFourDigits = substr ($phone, -4);
							$username       = Str::random (6).$lastFourDigits;
							$email          = $username.'@live.baze.co.ke';

							$user     = User::create ([
								                          'name' => $username, 'email' => $email, 'phone' => $phone, 'password' => Hash::make ($phone),
							                          ]);
							$at       = new AfricasTalking();
							$phoneNew = '0'.substr ($this->removeSpaces ($phone), -9);
							$at->send_sms ('baze', $phoneNew,
							               'Welcome to live.baze.co.ke . Kindly update your profile to enjoy a better experience.');
						}
					$otp = $this->generateOTP ();
					$at  = new AfricasTalking();
					$at->send_sms ('baze', $phone, 'Use '.$otp." to verify your access");
					$user->verification_key = $otp;
					$user->save ();

					// save the phone no to the session
					Session::put ('USER_PHONE_NUMBER', $phone);

					return view ('Frontend.auth.otp', ['id' => $user->id, 'phone' => $this->maskPhoneNumber ($phone)]);
				}

			public function phoneResend (Request $request)
				{
					$sessionPhoneNo = session ('USER_PHONE_NUMBER');

					$phone = $sessionPhoneNo;

					$user = User::where ('phone', $phone)->first ();

					if (is_null ($user))
						{
							// User does not exist, create a new account
							$lastFourDigits = substr ($phone, -4);
							$username       = Str::random (6).$lastFourDigits;
							$email          = $username.'@live.baze.co.ke';

							$user     = User::create ([
								                          'name' => $username, 'email' => $email, 'phone' => $phone, 'password' => Hash::make ($phone),
							                          ]);
							$at       = new AfricasTalking();
							$phoneNew = '0'.substr ($this->removeSpaces ($phone), -9);
							$at->send_sms ('baze', $phoneNew,
							               'Welcome to live.baze.co.ke . Kindly update your profile to enjoy a better experience.');
						}
					$otp = $this->generateOTP ();
					$at  = new AfricasTalking();
					$at->send_sms ('baze', $phone, 'Use '.$otp." to verify your access");
					$user->verification_key = $otp;
					$user->save ();

					// save the phone no to the session
					Session::put ('USER_PHONE_NUMBER', $phone);

					return view ('Frontend.auth.otp', ['id' => $user->id, 'phone' => $this->maskPhoneNumber ($phone)]);
				}

			public function otp_verify (Request $request)
				{
					$user = User::find ($request->user_id);

					if (!$user)
						{
							return redirect ()->intended ('/login')->with ('error', 'User not found.');
						}

					if ($user->verification_key === $request->otp)
						{
							//$check = (bool) Hash::check ($user->phone, $user->password);
							// Logging before attempting to log out other devices
							Log::info ("Attempting to log out other devices for user - old :{$user->stream_auth}");
							Auth::loginUsingId ($user->id);
							if (!is_null ($user->stream_auth) && $user->stream_auth !== session ()->getId ())
								{
									// Delete the previous session from the database
									DB::table ('sessions')->where ('user_id', $user->id)->delete ();
								}

							Session::regenerate ();
							$user->stream_auth      = session ()->getId ();
							$user->verification_key = null;
							$user->save ();
							event (LogoutUser::broadcast ($user->id));
							Log::info ("Attempting to log out other devices for user - new :{$user->stream_auth}");


							// Regenerate session again after login
							$request->session ()->regenerate ();


							return redirect ()->intended ('/')->with ('success', 'Logged in successfully');
						}

					return redirect ()->intended ('/phone-login')->with ('error',
					                                                     'The OTP does not match. Please try to login with the correct phone number.');
				}


			public function logout (Request $request)
				{
					$user = $request->user ();
					Session::getHandler ()->destroy ($user->stream_auth);
					Cache::forget ($user->stream_auth);
					$request->session ()->regenerate ();
					$user->stream_auth = null;
					$user->save ();
					DB::table ('sessions')->where ('user_id', $user->id)->delete ();
					Auth::logout ();

					$request->session ()->invalidate ();

					return redirect ()->intended ('/')->with ('success', 'Logout successful.');
				}

			public function forgotPassword (Request $request)
				{
					$request->validate (['email' => 'required|email']);

					$status = Password::sendResetLink ($request->only ('email'));

					return $status === Password::RESET_LINK_SENT ? back ()->with ('success',
					                                                              'Reset password link sent to your email.') : back ()->withErrors (['email' => 'Unable to send reset password link.']);
				}

			public function resetPassword (Request $request)
				{
					$request->validate ([
						                    'token' => 'required', 'email' => 'required|email', 'password' => 'required|min:8|confirmed',
					                    ]);

					$status = Password::reset ($request->only ('email', 'password', 'password_confirmation', 'token'),
						function ($user, $password)
							{
								$user->forceFill ([
									                  'password' => Hash::make ($password)
								                  ])->save ();
							});

					return $status === Password::PASSWORD_RESET ? redirect ()->route ('login')->with ('success',
					                                                                                  'Password reset successfully.') : back ()->withErrors (['email' => 'Unable to reset password.']);
				}

			public function verifyEmail (EmailVerificationRequest $request)
				{
					if ($request->user ()->hasVerifiedEmail ())
						{
							return back ()->with ('success', 'Email already verified.');
						}

					if ($request->user ()->markEmailAsVerified ())
						{
							return back ()->with ('success', 'Email verified successfully.');
						}

					return back ()->withErrors (['email' => 'Unable to verify email.']);
				}
		}
