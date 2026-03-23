<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Datatables\EventDatatable;
use App\Http\Requests\StoreEvent;
use App\Http\Requests\UpdateEvent;
use App\Http\Services\UploadService;
use App\Models\Content;
use App\Models\Event;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Traits\Meta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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

                try
                    {
                        if (!str_contains((string)$request->event_time, ' to '))
                            {
                                return self::failed('event', 'Invalid event time format', route('backend.event.index'));
                            }

                        [$startDateString, $endDateString] = explode(' to ', $request->event_time);

                        $startDate = Carbon::createFromFormat('Y/m/d h:i A', trim($startDateString));
                        $endDate   = Carbon::createFromFormat('Y/m/d h:i A', trim($endDateString));
                        $admin     = $request->user('admin');

                        $event                 = new Event();
                        $event->event_name     = $request->event_name;
                        $event->description    = $request->event_description;
                        $event->publish_date   = $request->publishdate;
                        $event->start_time     = $startDate;
                        $event->end_time       = $endDate;
                        $event->venue          = $request->venue;
                        $event->system_user_id = $admin->id;
                        $event->microsite_id   = $admin->microsite_id;
                        $event->status         = 1;
                        $event->is_featured    = $request->featured ?? 0;

                        if ($request->hasFile('thumbnail'))
                            {
                                $upload             = (new UploadService())->file_upload($request, 'thumbnail', 'event_image');
                                $event->event_image = $upload['path'];
                            }

                        $event->save();

                        if ($request->boolean('has_stream'))
                            {
                                $streamKey = Str::ulid();

                                $stream                    = new Content();
                                $stream->title             = $event->event_name;
                                $stream->description       = $request->event_description;
                                $stream->content_group     = 'livestream';
                                $stream->type              = 'application/x-mpegURL';
                                $stream->stream_key        = $streamKey;
                                $stream->stream_url        = config('custom.STREAM.LIVESTREAM_SERVER');
                                $stream->stream_video_link = config('custom.STREAM.LIVESTREAM_LINK') . '/' . $streamKey . '.m3u8';
                                $stream->start_time        = $startDate;
                                $stream->event_id          = $event->uuid;
                                $stream->system_user_id    = $admin->id;
                                $stream->microsite_id      = $admin->microsite_id;
                                $stream->status            = 1;

                                if ($request->hasFile('stream_thumbnail'))
                                    {
                                        $upload                = (new UploadService())->file_upload($request, 'stream_thumbnail', 'stream_thumbnail');
                                        $stream->thumbnail_url = $upload['path'];
                                    }

                                $stream->save();
                            }

                        $this->syncEventProducts($event, $request);

                        DB::commit();

                        return self::success('event', 'Saved successfully', route('backend.event.index'));
                    }
                catch (\Exception $e)
                    {
                        DB::rollBack();

                        Log::error('Event store failed: ' . $e->getMessage(), [
                            'trace' => $e->getTraceAsString(),
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
                $this->data['event'] = $event->load([
                    'products' => fn($query) => $query->with('variants')->orderBy('id'),
                ]);
                $this->data['title'] = $this->data['event']->event_name . ' Event : ' . $this->data['title'];

                return view('Backend.modules.event.edit', $this->data);
            }

    /**
     * Update the specified resource in storage.
     */
        public function update(UpdateEvent $request, $id)
            {
                DB::beginTransaction();

                try
                    {
                        if (!str_contains((string)$request->event_time, ' to '))
                            {
                                return self::failed('event', 'Invalid event time format', route('backend.event.index'));
                            }

                        [$startDateString, $endDateString] = explode(' to ', $request->event_time);

                        $startDate = Carbon::createFromFormat('Y/m/d h:i A', trim($startDateString));
                        $endDate   = Carbon::createFromFormat('Y/m/d h:i A', trim($endDateString));

                        $event = Event::findOrFail($id);
                        $admin = $request->user('admin');

                        $event->event_name     = $request->event_name;
                        $event->description    = $request->event_description;
                        $event->publish_date   = $request->publishdate;
                        $event->start_time     = $startDate;
                        $event->end_time       = $endDate;
                        $event->venue          = $request->venue;
                        $event->system_user_id = $admin->id;
                        $event->microsite_id   = $admin->microsite_id;
                        $event->status         = 1;
                        $event->is_featured    = $request->featured ?? 0;

                        if ($request->hasFile('thumbnail'))
                            {
                                $upload             = (new UploadService())->file_upload($request, 'thumbnail', 'event_image');
                                $event->event_image = $upload['path'];
                            }

                        $event->save();

                        $this->syncEventProducts($event, $request);

                        $stream = $event->streams()->first();

                        if ($request->boolean('has_stream'))
                            {
                                if (!$stream)
                                    {
                                        $stream                    = new Content();
                                        $stream->event_id          = $event->uuid;
                                        $stream->content_group     = 'livestream';
                                        $stream->type              = 'application/x-mpegURL';
                                        $stream->stream_key        = Str::ulid();
                                        $stream->stream_url        = config('custom.STREAM.LIVESTREAM_SERVER');
                                        $stream->stream_video_link = config('custom.STREAM.LIVESTREAM_LINK') . '/' . $stream->stream_key . '.m3u8';
                                    }

                                $stream->title          = $event->event_name;
                                $stream->description    = $event->description;
                                $stream->start_time     = $startDate;
                                $stream->system_user_id = $admin->id;
                                $stream->microsite_id   = $admin->microsite_id;
                                $stream->status         = 1;

                                if ($request->hasFile('stream_thumbnail'))
                                    {
                                        $upload                = (new UploadService())->file_upload($request, 'stream_thumbnail', 'stream_thumbnail');
                                        $stream->thumbnail_url = $upload['path'];
                                    }

                                $stream->save();
                            }
                        elseif ($stream)
                            {
                                $stream->delete();
                            }

                        DB::commit();

                        return self::success('event', 'Saved successfully', route('backend.event.index'));
                    }
                catch (\Exception $e)
                    {
                        DB::rollBack();

                        Log::error('Event update failed: ' . $e->getMessage(), [
                            'trace' => $e->getTraceAsString(),
                        ]);

                        return self::failed('event', 'An error occurred while updating', route('backend.event.index'));
                    }
            }

        private function syncEventProducts(Event $event, Request $request): void
            {
                $event
                    ->products()
                    ->whereIn('type', ['ticket', 'content', 'merch'])
                    ->delete();

                $this->saveTicketProducts($event, $request);
                $this->saveStreamProducts($event, $request);
                $this->saveMerchProducts($event, $request);
            }

        private function saveTicketProducts(Event $event, Request $request): void
            {
                $types      = $request->input('ticket.type', []);
                $quantities = $request->input('ticket.quantity', []);
                $currencies = $request->input('ticket.currency', []);
                $costs      = $request->input('ticket.cost', []);

                foreach ($types as $index => $type)
                    {
                        $type = trim((string)$type);
                        $cost = $costs[$index] ?? null;

                        if ($type === '' || $cost === null || $cost === '')
                            {
                                continue;
                            }

                        $event->products()->create([
                            'microsite_id' => $event->microsite_id,
                            'type'         => 'ticket',
                            'name'         => $type,
                            'price'        => $cost,
                            'currency'     => strtoupper((string)($currencies[$index] ?? 'USD')),
                            'stock_total'  => $this->nullableInteger($quantities[$index] ?? null),
                            'is_active'    => true,
                        ]);
                    }
            }

        private function saveStreamProducts(Event $event, Request $request): void
            {
                $names      = $request->input('stream.rate_name', []);
                $currencies = $request->input('stream.currency', []);
                $prices     = $request->input('stream.price', []);

                foreach ($names as $index => $name)
                    {
                        $name  = trim((string)$name);
                        $price = $prices[$index] ?? null;

                        if ($name === '' || $price === null || $price === '')
                            {
                                continue;
                            }

                        $event->products()->create([
                            'microsite_id' => $event->microsite_id,
                            'type'         => 'content',
                            'name'         => $name,
                            'price'        => $price,
                            'currency'     => strtoupper((string)($currencies[$index] ?? 'USD')),
                            'is_active'    => true,
                        ]);
                    }
            }

        private function saveMerchProducts(Event $event, Request $request): void
            {
                $names        = $request->input('merch.name', []);
                $currencies   = $request->input('merch.currency', []);
                $prices       = $request->input('merch.price', []);
                $images       = $request->file('merch.image', []);
                $variantNames = $request->input('merch.variants', []);

                foreach ($names as $index => $name)
                    {
                        $name  = trim((string)$name);
                        $price = $prices[$index] ?? null;

                        if ($name === '' || $price === null || $price === '')
                            {
                                continue;
                            }

                        $product = new Product([
                            'microsite_id' => $event->microsite_id,
                            'type'         => 'merch',
                            'name'         => $name,
                            'price'        => $price,
                            'currency'     => strtoupper((string)($currencies[$index] ?? 'USD')),
                            'is_active'    => true,
                        ]);

                        if (!empty($images[$index]))
                            {
                                $path                = $images[$index]->store('nowstream/merchandise/' . date('Y/m'), config('filesystems.default'));
                                $product->image_path = $path;
                            }

                        $event->products()->save($product);

                        $this->saveMerchVariants($product, $variantNames[$index] ?? []);
                    }
            }

        private function saveMerchVariants(Product $product, array $variants): void
            {
                $names          = $variants['name'] ?? [];
                $priceOverrides = $variants['price_override'] ?? [];
                $stocks         = $variants['stock_total'] ?? [];

                foreach ($names as $variantIndex => $variantName)
                    {
                        $variantName   = trim((string)$variantName);
                        $priceOverride = $priceOverrides[$variantIndex] ?? null;
                        $stockTotal    = $stocks[$variantIndex] ?? null;

                        if ($variantName === '')
                            {
                                continue;
                            }

                        $product->variants()->create([
                            'name'           => $variantName,
                            'price_override' => $priceOverride !== null && $priceOverride !== '' ? $priceOverride : null,
                            'stock_total'    => $this->nullableInteger($stockTotal),
                        ]);
                    }
            }

        private function nullableInteger($value): ?int
            {
                if ($value === null || $value === '')
                    {
                        return null;
                    }

                return (int)$value;
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
                    8 => 'publish_date',
                ];

                return response()->json($datatable->data($request));
            }
    }
