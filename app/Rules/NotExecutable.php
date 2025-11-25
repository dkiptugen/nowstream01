<?php
	
	namespace App\Rules;
	
	use Illuminate\Contracts\Validation\Rule;
	use Illuminate\Http\UploadedFile;
	
	class NotExecutable implements Rule
		{
		/**
		 * Determine if the validation rule passes.
		 *
		 * @param  string  $attribute
		 * @param  mixed   $value
		 *
		 * @return bool
		 */
			public function passes ($attribute, $value)
				{
					if ($value instanceof UploadedFile)
						{
							$content = file_get_contents ($value->getRealPath ());
							
							// Check for common executable file signatures (magic numbers)
							$executableSignatures = [
								"\x4D\x5A", // MZ - DOS/Windows executable
								"\x7F\x45\x4C\x46", // ELF - Unix/Linux executable
								"\x23\x21", // #! - Script files (e.g., Bash, Python)
							];
							
							foreach ($executableSignatures as $signature)
								{
									if (strpos ($content, $signature) === 0)
										{
											return false;
										}
								}
						}
					
					return true;
				}
		
		/**
		 * Get the validation error message.
		 *
		 * @return string
		 */
			public function message ()
				{
					return 'The :attribute must not be an executable file.';
				}
		}
