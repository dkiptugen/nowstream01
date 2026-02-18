<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Datatables\EventDatatable;
use App\Http\Requests\StoreEvent;
use App\Http\Requests\UpdateEvent;
use App\Http\Services\UploadService;
use App\Models\Channel;
use App\Models\Event;
use App\Models\Content;
use App\Traits\Meta;
use Carbon\Carbon;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB; 

class EventController extends Controller
{
    use Meta;

    public $data = [];

    public function __construct()
    {
        $this->data = self::product_def();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $this->data['title'] = 'Events : ' . $this->data['title'];
        return view('Backend.modules.event.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $this->data['title'] = 'Events : ' . $this->data['title'];
        return view('Backend.modules.event.add', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEvent $request)
    {
        DB::beginTransaction();

        try {

            // Validate time format
            if (!str_contains($request->event_time, ' - ')) {
                return self::failed('event', 'Invalid event time format', route('backend.event.index'));
            }

            [$startDateString, $endDateString] = explode(' - ', $request->event_time);

            $startDate = Carbon::createFromFormat('Y/m/d h:i A', trim($startDateString));
            $endDate   = Carbon::createFromFormat('Y/m/d h:i A', trim($endDateString));

            $admin = $request->user('admin');

            // Create Event
            $event = new Event();
            $event->event_name   = $request->event_name;
            $event->description  = $request->event_description;
            $event->publish_date = $request->publishdate;
            $event->start_time   = $startDate;
            $event->end_time     = $endDate;
            $event->venue        = $request->venue;
            $event->system_user_id = $admin->id;
            $event->status = 1;

            // Event thumbnail
            if ($request->hasFile('thumbnail')) {
                $upload = (new UploadService())->file_upload($request, 'thumbnail', 'event_image');
                $event->event_image = $upload['path'];
            }

            $event->save();

            /*
        |--------------------------------------------------------------------------
        | Create Livestream (optional)
        |--------------------------------------------------------------------------
        */
            if ($request->boolean('has_stream')) {

                $streamKey = Str::ulid();

                $stream = new Content();
                $stream->title         = $event->event_name;
                $stream->description   = $request->event_description;
                $stream->content_group = 'livestream';
                $stream->type          = 'application/x-mpegURL';
                $stream->stream_key    = $streamKey;
                $stream->stream_url    = config('custom.STREAM.LIVESTREAM_SERVER');
                $stream->stream_video_link =
                    config('custom.STREAM.LIVESTREAM_LINK') . '/' . $streamKey . '.m3u8';

                $stream->start_time     = $startDate;
                $stream->event_id       = $event->id;
                $stream->system_user_id = $admin->id;
                $stream->channel_id     = $admin->channel_id ?? null;
                $stream->status         = 1;

                // Stream thumbnail
                if ($request->hasFile('stream_thumbnail')) {
                    $upload = (new UploadService())->file_upload($request, 'stream_thumbnail', 'stream_thumbnail');
                    $stream->thumbnail_url = $upload['path'];
                }

                $stream->save();
            }

            DB::commit();

            return self::success('event', 'Saved successfully', route('backend.event.index'));
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Event store failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return self::failed('event', 'Something went wrong. Please try again.', route('backend.event.index'));
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {

        $this->data['event'] = $event; 
        $this->data['title'] = $this->data['event']->title . ' Event : ' . $this->data['title'];
        return view('Backend.modules.event.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     */
  public function update(UpdateEvent $request, $id)
{
    try {

        // =============================
        // Parse Event Time
        // =============================
        [$startDateString, $endDateString] = explode(' - ', $request->event_time);

        $startDate = Carbon::createFromFormat('Y/m/d h:i A', $startDateString);
        $endDate   = Carbon::createFromFormat('Y/m/d h:i A', $endDateString);

        $validated = $request->validated();

        if (!$validated) {
            return self::failed('event', $validated, route('backend.event.index'));
        }

        // =============================
        // Update Event
        // =============================
        $event = Event::findOrFail($id);

        $event->event_name   = $request->event_name;
        $event->description  = $request->event_description;
        $event->publish_date = $request->publishdate;
        $event->start_time   = $startDate;
        $event->end_time     = $endDate;
        $event->venue        = $request->venue;
        $event->system_user_id = $request->user('admin')->id;
        $event->channel_id     = $request->user()->channel_id;
        $event->status         = 1;

        // Event image
        if ($request->hasFile('thumbnail')) {
            $upload = (new UploadService())->file_upload(
                $request,
                'thumbnail',
                'event_image',
                'linode'
            );
            $event->event_image = $upload['path'];
        }

        $event->save();

        // =============================
        // Handle Livestream
        // =============================
        $stream = $event->streams()->first();

        if ($request->has('has_stream')) {

            // Create if not exists
            if (!$stream) {
                $stream = new Content();
                $stream->event_id        = $event->id;
                $stream->content_group   = 'livestream';
                $stream->type            = 'application/x-mpegURL';
                $stream->stream_key      = \Str::ulid();
                $stream->stream_url      = config('custom.STREAM.LIVESTREAM_SERVER');
                $stream->stream_video_link = config('custom.STREAM.LIVESTREAM_LINK')
                    . '/' . $stream->stream_key . '.m3u8';
            }

            // Update stream
            $stream->title           = $event->event_name;
            $stream->description     = $event->description;
            $stream->start_time      = $startDate;
            $stream->system_user_id  = $request->user('admin')->id;
            $stream->channel_id      = $request->user()->channel_id;
            $stream->status          = 1;

            // Stream thumbnail
            if ($request->hasFile('stream_thumbnail')) {
                $upload = (new UploadService())->file_upload(
                    $request,
                    'stream_thumbnail',
                    'stream_thumbnail'
                );
                $stream->thumbnail_url = $upload['path'];
            }

            $stream->save();

        } else {
            // If unchecked, delete existing stream
            if ($stream) {
                $stream->delete();
            }
        }

        return self::success(
            'event',
            'Saved successfully',
            route('backend.event.index')
        );

    } catch (\Exception $e) {
        Log::error('Event Update Error: ' . $e->getMessage());
        return self::failed(
            'event',
            'An error occurred while updating',
            route('backend.event.index')
        );
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($channelId, $id) {}

    public function datatable(Request $request, EventDatatable $datatable)
    {


        $datatable->columns = [
            1 => 'event_name',
            2 => 'thumbnail',
            7 => 'created_at',
            6 => 'status',
            8 => 'publish_date'
        ];
        return response()->json($datatable->data($request));
    }
}
