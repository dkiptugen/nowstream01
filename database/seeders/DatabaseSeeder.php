<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Channel;
use App\Models\Event;
use App\Models\Stream;
use App\Models\StreamPartner;
use App\Models\Video;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([UserSeeder::class,ChannelSeeder::class,SystemUserChannelSeeder::class]);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        /*StreamPartner::factory(300)->create();
        Channel::factory(1500)->create();
        Event::factory(1500)->create();
		Video::factory(3000)->create();
		Stream::factory (3000)->create();*/

    }
}
