<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Event;
use Illuminate\Support\Facades\Cache;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    public function download($uuid)
    {
        $user = auth()->user();

        $ticket = Ticket::with('event')->where('uuid', $uuid)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $event = $ticket->event;

        if (!$event) {
            abort(404, 'Event not found for this ticket.');
        }
 
        // Generate QR data
        $qrData = route('ticket.verify', $ticket->uuid);

        $pdf = Pdf::loadView('Frontend.modules.events.ticket', [
            'ticket' => $ticket,
            'event'  => $event,
            'user'   => $user,
            'qrData' => $qrData
        ])->setPaper('a6', 'portrait');

        return $pdf->download("ticket_{$ticket->uuid}.pdf");
    }
    public function verify($uuid)
    {
        $ticket = Ticket::where('uuid', $uuid)->with('event')->firstOrFail();

        return view('Frontend.modules.events.verify', compact('ticket'));
    }
}
