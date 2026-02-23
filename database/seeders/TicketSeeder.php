<?php

namespace Database\Seeders; 

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Str;

class TicketSeeder extends Seeder
{
    public function run()
    {
        // Pick a user (or first)
        $user = User::first();

        // Pick an event (or first)
        $event = Event::first();

        if (!$user || !$event) {
            $this->command->info('No users or events found. Seed aborted.');
            return;
        }

        Ticket::create([
            'uuid' => Str::uuid(),
            'user_id' => $user->id,
            'event_id' => $event->uuid, // match events.uuid
            'ticket_number' => 'TICKET-' . strtoupper(Str::random(8)),
            'type' => 'General',
            'price' => 1000, // adjust as needed
            'is_used' => false,
        ]);

        $this->command->info('1 Ticket seeded successfully!');
    }
}
