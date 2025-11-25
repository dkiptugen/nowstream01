<?php

namespace Database\Seeders;

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
        SystemUserChannel::firstOrCreate(['channel_id'=>1,'system_user_id'=>1,'created_by'=>1]);
    }
}
