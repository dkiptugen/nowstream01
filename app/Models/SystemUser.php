<?php

    namespace App\Models;

    // use Illuminate\Contracts\Auth\MustVerifyEmail;
    use Illuminate\Database\Eloquent\Casts\Attribute;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Foundation\Auth\User as Authenticatable;
    use Illuminate\Notifications\Notifiable;
    use Laravel\Sanctum\HasApiTokens;
    use NotificationChannels\WebPush\HasPushSubscriptions;
    use Spatie\Permission\Traits\HasRoles;


    class SystemUser extends Authenticatable
        {
            use HasApiTokens,HasFactory,Notifiable,HasRoles,HasPushSubscriptions;

        /**
         * The attributes that are mass assignable.
         *
         * @var array<int, string>
         */
            protected $fillable
                = [
                    'name',
                    'email',
                    'password',
                ];

        /**
         * The attributes that should be hidden for serialization.
         *
         * @var array<int, string>
         */
            protected $hidden
                = [
                    'password',
                    'remember_token',
                ];
        /**
         * The attributes that should be cast.
         *
         * @var array<string, string>
         */
            protected $casts
                = [
                    'email_verified_at' => 'datetime',
                    'password'          => 'hashed',
                ];

            public function channels()
                {
                    return $this->belongsToMany( Channel::class ,'system_user_channel')->using( SystemUserChannel::class );
                }
		    public function getModelAttributes()
			    {
				    $attributes = $this->getAttributes(); // Using getAttributes() method
				    return $attributes;
			    }
            public function  ActiveChannel() :Attribute
	            {
					//dd();
		            $channel = Channel::whereIdentifier($this->attributes['user_active_channel'])->first();
		           return Attribute::make (get: fn($value) => $channel);
	            }
        }
