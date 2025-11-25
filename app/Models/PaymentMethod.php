<?php
	
	namespace App\Models;
	
	use App\Casts\JsonCast;
	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;
	
	class PaymentMethod extends Model
		{
			use HasFactory;
			
			protected $casts =  [
									'configuration' => JsonCast::class,
									'notification_endpoints' =>JsonCast::class
								];
			
			public function user ()
				{
					return $this->belongsTo (SystemUser::class);
				}
		}
