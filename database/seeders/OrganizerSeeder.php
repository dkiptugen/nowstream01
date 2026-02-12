<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organizer;

class OrganizerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default organizer
        Organizer::create([
            'user_id'     => 1,  
            'name'        => 'Default Organizer',
            'description' => 'Organizer for all sample events',
            'logo_path'   => null,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }
}
