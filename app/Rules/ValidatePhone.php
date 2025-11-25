<?php
	
	namespace App\Rules;
	
	use Illuminate\Contracts\Validation\Rule;
	
	
	class ValidatePhone implements Rule
		{
			protected $prefixes = [
				'700', '701', '702', '703', '704', '705', '706', '707', '708', '709',
				'710', '711', '712', '713', '714', '715', '716', '717', '718', '719',
				'720', '721', '722', '723', '724', '725', '726', '727', '728', '729',
				'740', '741', '742', '743', '745', '746', '748', '757', '758', '759',
				'768', '769', '790', '791', '792', '793', '794', '795', '796', '797',
				'798', '799', '110', '111', '112', '113', '114', '115'
			];
			
			public function passes($attribute, $value):bool
				{
					// Remove the country code if present
					$value = preg_replace('/[\s-]+/', '', $value);
					$value = substr ($value,-9,3);
					if(in_array($value,$this->prefixes))
						return true;
					return false;
				}
		
		/**
		 * Get the validation error message.
		 *
		 * @return string
		 */
			public function message ()
				{
					return 'Kindly Enter a Mpesa Mobile Number';
				}
		}
