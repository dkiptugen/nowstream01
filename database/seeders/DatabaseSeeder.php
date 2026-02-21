<?php

    namespace Database\Seeders;

    // use Illuminate\Database\Console\Seeds\WithoutModelEvents;
    use App\Models\Channel;
    use App\Models\Event;
    use App\Models\Content;
    use App\Models\ContentPartner;
    use App\Models\Video;
    use Illuminate\Database\Seeder;

    class DatabaseSeeder extends Seeder
        {
        /**
         * Seed the application's database.
         */
            public function run()
            : void
                {
                    $this->call([UserSeeder::class]);


                }
        }
