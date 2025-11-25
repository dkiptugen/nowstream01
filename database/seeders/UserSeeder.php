<?php
	
	namespace Database\Seeders;
	
	use App\Models\SystemUser;
	use Illuminate\Database\Console\Seeds\WithoutModelEvents;
	use Illuminate\Database\Seeder;
	
	class UserSeeder extends Seeder
		{
			use WithoutModelEvents;
		
		/**
		 * Run the database seeds.
		 */
			public function run ()
			: void
				{
					$user = (new SystemUser())->updateOrCreate ([
							'email' => 'info@laughindustries.com'
						], [
							
							"name" => "Default Administrator",
							"password" => bcrypt ('1234567'),
							"status" => true,
							"type" => 'owner'
						
						]);
					$user->assignRole ('Super Admin');
				}
		}
