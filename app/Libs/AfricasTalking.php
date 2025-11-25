<?php
	
	namespace App\Libs;
	
	use AfricasTalking\SDK\AfricasTalking as ATSDK;
	
	class AfricasTalking
		{
			
			
			public function send_sms ($shortcode, $msisdn, $message)
				{
					$username = config ('custom.SMS.AFRICAS_TALKING_USERNAME'); // use 'sandbox' for development in the test environment
					$apiKey   = config ('custom.SMS.AFRICAS_TALKING_API_KEY');  // use your sandbox app API key for development in the test environment
					$AT       = new ATSDK($username, $apiKey);
					
					// Get one of the services
					$sms = $AT->sms ();
					
					// Use the service
					$result = $sms->send ([
						'from' => $shortcode, 'to' => $msisdn, 'message' => $message,'bulkSMSMode'=>1
					]);
					
					return response()->json($result);
				}
			
		}