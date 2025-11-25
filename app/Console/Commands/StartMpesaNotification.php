<?php
	
	namespace App\Console\Commands;
	
	use App\Libs\Mpesa;
	use Illuminate\Console\Command;
	
	class StartMpesaNotification extends Command
		{
		/**
		 * The name and signature of the console command.
		 *
		 * @var string
		 */
			protected $signature = 'mpesa:notification';
		
		/**
		 * The console command description.
		 *
		 * @var string
		 */
			protected $description = 'Start mpesa notification on: validation';
		
		/**
		 * Execute the console command.
		 */
			public function handle()
				{
					$mpesa                   = new Mpesa('production');
					$mpesa->consumerkey      = config('mpesa.consumer_key');
					$mpesa->consumersecret   = config('mpesa.consumer_secret');
					$mpesa->validation_url   = route('mpesa.validation');
					$mpesa->confirmation_url = route('mpesa.confirmation');
					$mpesa->shortcode        = config('mpesa.paybill');
					//dd($mpesa);
					$data                    = $mpesa->RegisterURL();
					//dd($data);
					return $this->info($data);
				}
		}
