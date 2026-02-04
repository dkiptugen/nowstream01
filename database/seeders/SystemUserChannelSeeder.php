<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\SystemUserChannel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemUserChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $channel = Channel::first();
        SystemUserChannel::firstOrCreate(['channel_id'=>$channel->uuid,'system_user_id'=>1,'created_by'=>1]);
    }
}
