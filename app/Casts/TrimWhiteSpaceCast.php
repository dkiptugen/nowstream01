<?php

    namespace App\Casts;

    use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
    use Illuminate\Database\Eloquent\Model;

    class TrimWhiteSpaceCast implements CastsAttributes
        {
        /**
         * Cast the given value.
         *
         * @param array<string, mixed> $attributes
         */
            public function get($model, string $key, $value, array $attributes)
                {
                    return trim($value);
                }

        /**
         * Prepare the given value for storage.
         *
         * @param array<string, mixed> $attributes
         */
            public function set($model, string $key, $value, array $attributes)
                {
                    return trim($value);
                }
        }
