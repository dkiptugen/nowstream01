<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Product;
use Illuminate\Support\Str;

class EventRatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all active events
        $events = Event::where('status', 1)->get();

        foreach ($events as $event) { 
            $existingTickets = Product::where('event_id', $event->id)
                                      ->where('type', 'ticket')
                                      ->count();

            if ($existingTickets > 0) {
                continue;
            }
 
            $ticketTypes = [
                ['name' => 'VIP',     'min' => 5000, 'max' => 8000],
                ['name' => 'Regular', 'min' => 1500, 'max' => 4000],
                ['name' => 'Student', 'min' => 500,  'max' => 1500],
                ['name' => 'Stream',   'min' => 200,  'max' => 800],
            ];

            foreach ($ticketTypes as $ticket) {
                Product::create([
                    'organizer_id'   => 1,
                    'event_id'       => $event->id,
                    'type'           => 'ticket',
                    'free_pass'      => 0,
                    'name'           => $ticket['name'],
                    'description'    => "{$ticket['name']} ticket for {$event->event_name}",
                    'price'          => rand($ticket['min'], $ticket['max']),
                    'currency'       => 'KES',
                    'stock_total'    => rand(50, 200),
                    'stock_sold'     => 0,
                    'sales_start_at' => now(),
                    'sales_end_at'   => $event->start_time ?? now()->addDays(30),
                    'is_active'      => 1,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }
        }

        $this->command->info('Event ticket rates seeded successfully.');
    }
}
