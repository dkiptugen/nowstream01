<?php

namespace App\Traits;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait HasUuid
    {
        protected static function bootHasUuid(): void
            {

                static::creating(function ($model)
                    {
                        do
                            {
                                // Generate a new UUID or ULID
                                $model->uuid = (string)Str::uuid(); // or Str::ulid() if using ULID
                                $unique      = true;

                                try
                                    {
                                        // Check if this UUID already exists
                                        if ($model::where('uuid', $model->uuid)->exists())
                                            {
                                                $unique = false;
                                            }
                                    }
                                catch (QueryException $e)
                                    {
                                        Log::error($e->getMessage());
                                        // In rare race condition cases, treat DB exception as collision
                                        $unique = false;
                                    }

                            } while (!$unique);

                    });
            }
    }
