<?php

    namespace App\Http\Controllers\API;

    use App\Http\Controllers\Controller;
    use App\Libs\AfricasTalking;
    use App\Models\User;
    use App\Rules\ValidatePhone;
    use App\Traits\Helper;
    use GuzzleHttp\Exception\ClientException;
    use Illuminate\Http\RedirectResponse;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Support\Str;
    use Laravel\Socialite\Facades\Socialite;

    class MobileAuthController extends Controller
        {
            use Helper;

            private const DEFAULT_APP_REDIRECT_URI = 'tvapp://auth-callback';

            public function socialRedirect(Request $request, string $provider)
            : RedirectResponse
                {
                    $provider    = $this->normalizeProvider($provider);
                    $redirectUri = $this->resolveAppRedirectUri($request->query('redirect_uri'));
                    $callbackUrl = route('api.auth.social.callback', ['provider' => $provider]);
                    $state       = $this->encodeState(['redirect_uri' => $redirectUri]);

                    return Socialite::driver($provider)
                                    ->stateless()
                                    ->redirectUrl($callbackUrl)
                                    ->with(['state' => $state])
                                    ->redirect();
                }

            public function socialCallback(Request $request, string $provider)
            : RedirectResponse
                {
                    $provider    = $this->normalizeProvider($provider);
                    $redirectUri = $this->resolveRedirectUriFromState($request->query('state'));

                    try
                        {
                            $socialUser = Socialite::driver($provider)
                                                   ->stateless()
                                                   ->redirectUrl(route('api.auth.social.callback', ['provider' => $provider]))
                                                   ->user();
                        }
                    catch (ClientException $exception)
                        {
                            Log::warning('Mobile social login failed.', [
                                'provider' => $provider,
                                'message'  => $exception->getMessage(),
                            ]);

                            return redirect()->away($this->buildRedirectUri($redirectUri, [
                                'status'  => 'error',
                                'message' => 'Social login failed.',
                            ]));
                        }

                    $email      = $socialUser->getEmail();
                    $providerId = (string)$socialUser->getId();

                    if ($email === null || trim($email) === '')
                        {
                            $email = sprintf('%s_%s@streamer.co.ke', $provider, $providerId);
                        }

                    $user = User::firstOrCreate(
                        ['email' => $email],
                        [
                            'email_verified_at' => now(),
                            'name'              => $socialUser->getName() ?: ucfirst($provider) . ' User',
                            'status'            => 1,
                            'password'          => Hash::make(Str::random(32)),
                        ]
                    );

                    if ((int)$user->status !== 1)
                        {
                            $user->status = 1;
                            $user->save();
                        }

                    $user->providers()->updateOrCreate(
                        [
                            'provider'    => $provider,
                            'provider_id' => $providerId,
                        ],
                        [
                            'avatar' => $socialUser->getAvatar(),
                        ]
                    );

                    $token = $user->createToken('mobile-social-auth')->plainTextToken;

                    return redirect()->away($this->buildRedirectUri($redirectUri, [
                        'status'   => 'success',
                        'token'    => $token,
                        'email'    => $user->email,
                        'provider' => $provider === 'twitter' ? 'x' : $provider,
                        'name'     => $user->name,
                    ]));
                }

            public function requestPhoneOtp(Request $request)
                {
                    $validator = Validator::make($request->all(), [
                        'phone' => ['required', 'string', 'max:15', new ValidatePhone()],
                    ]);

                    if ($validator->fails())
                        {
                            return response()->json([
                                                        'status' => false,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            'responseCode' => 422,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           'message' => $validator->errors()->first(),
                                                        'data'   => [],
                                                    ], 422);
                        }

                    $phone = $this->normalizePhone($request->input('phone'));
                    $user  = User::where('phone', $phone)->first();

                    if ($user === null)
                        {
                            $lastFourDigits = substr($phone, -4);
                            $username       = Str::random(6) . $lastFourDigits;

                            $user = User::create([
                                                     'name'     => $username,
                                                     'email'    => $username . '@streamer.co.ke',
                                                     'phone'    => $phone,
                                                     'status'   => 1,
                                                     'password' => Hash::make(Str::random(32)),
                                                 ]);
                        }
                    elseif ((int)$user->status !== 1)
                        {
                            $user->status = 1;
                        }

                    $otp                    = $this->generateOtp();
                    $user->verification_key = $otp;
                    $user->save();

                    try
                        {
                            $sms = new AfricasTalking();
                            $sms->send_sms('baze', $phone, 'Use ' . $otp . ' to verify your access');
                        }
                    catch (\Throwable $exception)
                        {
                            Log::error('Phone OTP SMS failed.', [
                                'phone'   => $phone,
                                'message' => $exception->getMessage(),
                            ]);

                            return response()->json([
                                                        'status' => false,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            'responseCode' => 500,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           'message' => 'Failed to send OTP.',
                                                        'data'   => [],
                                                    ], 500);
                        }

                    return response()->json([
                                                'status'       => true,
                                                'responseCode' => 200,
                                                'message'      => 'OTP sent successfully.',
                                                'data'         => [
                                                    'phone'        => $phone,
                                                    'masked_phone' => $this->maskPhoneNumber($phone),
                                                ],
                                            ]);
                }

            public function verifyPhoneOtp(Request $request)
                {
                    $validator = Validator::make($request->all(), [
                        'phone' => ['required', 'string', 'max:15', new ValidatePhone()],
                        'otp'   => ['required', 'string', 'size:6'],
                    ]);

                    if ($validator->fails())
                        {
                            return response()->json([
                                                        'status' => false,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            'responseCode' => 422,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           'message' => $validator->errors()->first(),
                                                        'data'   => [],
                                                    ], 422);
                        }

                    $phone = $this->normalizePhone($request->input('phone'));
                    $user  = User::where('phone', $phone)->first();

                    if ($user === null)
                        {
                            return response()->json([
                                                        'status' => false,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            'responseCode' => 404,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           'message' => 'User not found.',
                                                        'data'   => [],
                                                    ], 404);
                        }

                    if ($user->verification_key !== $request->input('otp'))
                        {
                            return response()->json([
                                                        'status' => false,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            'responseCode' => 422,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           'message' => 'The OTP does not match.',
                                                        'data'   => [],
                                                    ], 422);
                        }

                    $user->verification_key = null;
                    $user->status           = 1;
                    $user->save();

                    $token = $user->createToken('mobile-phone-auth')->plainTextToken;

                    return response()->json([
                                                'status'       => true,
                                                'responseCode' => 200,
                                                'message'      => 'Phone login successful.',
                                                'data'         => [
                                                    'token' => $token,
                                                    'email' => $user->email,
                                                    'phone' => $user->phone,
                                                    'name'  => $user->name,
                                                ],
                                            ]);
                }

            private function normalizeProvider(string $provider)
            : string
                {
                    $provider = strtolower(trim($provider));

                    return match ($provider)
                        {
                        'x'                             => 'twitter',
                        'google', 'facebook', 'twitter' => $provider,
                        default                         => abort(404),
                        };
                }

            private function resolveAppRedirectUri(?string $redirectUri)
            : string
                {
                    $redirectUri = trim((string)$redirectUri);

                    if ($redirectUri === '' || !str_starts_with($redirectUri, 'tvapp://'))
                        {
                            return self::DEFAULT_APP_REDIRECT_URI;
                        }

                    return $redirectUri;
                }

            private function encodeState(array $payload)
            : string
                {
                    return rtrim(strtr(base64_encode(json_encode($payload)), '+/', '-_'), '=');
                }

            private function resolveRedirectUriFromState(?string $state)
            : string
                {
                    if ($state === null || $state === '')
                        {
                            return self::DEFAULT_APP_REDIRECT_URI;
                        }

                    $decoded = base64_decode(strtr($state, '-_', '+/'), true);
                    if ($decoded === false)
                        {
                            return self::DEFAULT_APP_REDIRECT_URI;
                        }

                    $payload = json_decode($decoded, true);
                    if (!is_array($payload))
                        {
                            return self::DEFAULT_APP_REDIRECT_URI;
                        }

                    return $this->resolveAppRedirectUri($payload['redirect_uri'] ?? null);
                }

            private function buildRedirectUri(string $redirectUri, array $query)
            : string
                {
                    $separator = str_contains($redirectUri, '?') ? '&' : '?';

                    return $redirectUri . $separator . http_build_query($query);
                }

            private function normalizePhone(string $phone)
            : string
                {
                    return $this->removeSpaces('254' . substr($phone, -9));
                }

            private function generateOtp(int $length = 6)
            : string
                {
                    $otp = '';
                    for ($index = 0; $index < $length; $index++)
                        {
                            $otp .= mt_rand(0, 9);
                        }

                    return $otp;
                }
        }
