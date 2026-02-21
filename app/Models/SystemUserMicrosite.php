<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\Pivot;

    class SystemUserMicrosite extends Pivot
        {
            use HasFactory;

            protected $table    = 'system_user_microsite';
            protected $fillable = ['created_by', 'system_user_id', 'microsite_id', 'role_id'];

            public function microsite()
                {
                    return $this->belongsTo(Microsite::class, 'microsite_id', 'uuid');
                }

            public function system_user()
                {
                    return $this->belongsTo(SystemUser::class);
                }

            public function role()
                {
                    return $this->belongsTo(Role::class);
                }
        }
