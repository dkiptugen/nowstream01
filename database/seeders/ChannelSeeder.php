<?php

namespace Database\Seeders;

use App\Models\Channel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ChannelSeeder extends Seeder
{
	
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $channel = Channel::create(  ['identifier' => Str::random(9),
            'name' => "Churchill Show",
            'thumbnail' => 'https://cdn.standardmedia.co.ke/sdemedia/sdeimages/monday/gzictwzkxwdcxcp9bm5b30a3f7c208e.jpg',
            'cover_image' => 'https://upload.wikimedia.org/wikipedia/en/d/df/Churchill_Show_logo.jpeg',
            'description' => 'Churchill Show (then:Churchill Live) premiered on NTV (Kenya) in 2007 and continued until late 2009. The series returned for a second season on January 17, 2013. It originally aired Thursdays at 8 p.m. EAT,[1] but was later moved to Sundays at 8 p.m. As of November 2014, in its fifth season, it was the network\'s most viewed show and one of the most watched in East Africa',
            'status' => 1,
            'stream_partner_id' => 1,
            ]);
    }
}
