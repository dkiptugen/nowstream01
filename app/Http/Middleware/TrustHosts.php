<?php

    namespace App\Http\Middleware;

    use Illuminate\Http\Middleware\TrustHosts as Middleware;

    class TrustHosts extends Middleware
        {
        /**
         * Get the host patterns that should be trusted.
         *
         * @return array<int, string|null>
         */
            public function hosts()
            : array
                {

                    $baseDomain = preg_quote(config('app.base_domain'));
                    return [
                        'localhost',
                        '^(.+\.)?' . $baseDomain . '$',
                    ];
                }
        }
