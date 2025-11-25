<?php
	
	namespace App\Console\Commands;
	

	use App\Libs\AfricasTalking;
	use App\Libs\SafaricomContent;
	use App\Models\SystemUser;
	use App\Models\Video;
	use App\Traits\Helper;

	use Illuminate\Console\Command;
	use Illuminate\Support\Facades\File;
	use Illuminate\Support\Facades\Storage;
	
	
	class Test extends Command
		{
			use Helper;
		/**
		 * The name and signature of the console command.
		 *
		 * @var string
		 */
			protected $signature = 'app:test';
		
		/**
		 * The console command description.
		 *
		 * @var string
		 */
			protected $description = 'Command description';
		
		/**
		 * Execute the console command.
		 */

			public function handle ()
				{
					$file = Storage::disk('videos')->get('007.mp4');
					$this->info($file);
					/*	$test = '+254713154085';
					$this->info(substr ($test,-9,3));*/
				/*
					$at = new AfricasTalking();
					$mes= $at->send_sms ('baze','0713154085','Test Message from baze live');
					dd($mes);
					*/
					/*$user = (new SystemUser())->updateOrCreate ([
																	'email' => 'info@bazelive.co.ke'
																], [
																	
																	"name" => "Baze Live API User",
																	"password" => bcrypt ('RE9f98allANpxgBJlmzxJTs8bmE2U2C3'),
																	"status" => true,
																	"type" => 'owner'
																
																]);*/
					/*$directoryPath = public_path ('videos'); // Replace 'your-directory' with the path to your directory
					$files         = File::files ($directoryPath);
					
					foreach ($files as $file)
						{
							$name  = $file->getFilename ();
							$filex = explode ('.', $name);
							//dd ($filex);
							Video::updateOrCreate (['title' => $filex[0]], [
								                                             'channel_id'     => 1,
								                                             'event_id'       => 1,
								                                             'description'    => $name,
								                                             'video_path'     => '/videos/'.$name,
								                                             'system_user_id' => 1
							                                             ]
							);
						}*/
				}

		}
