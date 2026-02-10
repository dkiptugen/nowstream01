<?php


	namespace App\Http\Controllers\Backend;

	use App\Http\Datatables\EventRateDatatable;
	use App\Models\ContentRate;
	use App\Models\Event;
    use App\Traits\Meta;
    use Illuminate\Http\Request;
	use App\Http\Controllers\Controller;

	class EventRateController extends Controller
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

			public function index($id)
				{
					$event                    = Event::findOrFail($id);
					$this->data['event']      = $event;
					$eventRates               = ContentRate::where('status', 1)->where('event_id', $id)->get();
					$this->data['eventRates'] = $eventRates;
					return view('Backend.modules.event.rates.index', $this->data);
				}

			public function create($id)
				{
					try
						{
							$event               = Event::findOrFail($id);
							$this->data['event'] = $event;
							return view('Backend.modules.event.rates.create', $this->data);

						}
					catch (\Exception $e)
						{
							abort(404, 'event not found');
						}
				}

		// Store a newly created event rate in storage
			public function store(Request $request, $event)
				{
					$request->validate([
						                   'name'                   => 'required|string|max:255',
						                   'cost'                       => 'nullable|numeric',
						                   'date_from'              => 'required|string|max:255',
						                   'date_to'                => 'required|string|max:255',
						                   'reserved_currency_cost' => 'nullable|numeric',
						                   'status'                 => 'required|integer|min:0|max:1'
					                   ]);

					$eventRate           = new ContentRate($request->all());
					$eventRate->event_id = $event;
					$eventRate->visible = 1;
					$eventRate->save();

					return redirect()->route('backend.event.rates.index', ['event' => $event])->with('success', 'Event rate created successfully.');
				}

		/**
		 * Display the specified resource.
		 */
			public function show(string $id)
				{
					//
				}

		/**
		 * Show the form for editing the specified resource.
		 */ // Display a listing of the event rates
		// Show the form for editing the specified event rate
			public function edit($eventId, $id)
				{
					$eventRate               = ContentRate::with('event')->findOrFail($id);
					$this->data['eventRate'] = $eventRate;

					$this->data['events'] = Event::all();
					return view('Backend.modules.event.rates.edit', $this->data);
				}


		// Update the specified event rate in storage
			public function update(Request $request, $eventId, $id)
				{
					try
						{
							$validatedData = $request->validate([
								                                    'name'                   => 'required|string|max:255',
								                                    'cost'                   => 'nullable|numeric',
								                                    'reserved_currency_cost' => 'nullable|numeric',
								                                    'status'                 => 'required|integer|in:0,1',
							                                    ]);

							// Assuming you want to update event_id if it's being updated
							$eventRate                         = ContentRate::find($id);
							$eventRate->event_id               = $eventId;
							$eventRate->name                   = $validatedData['name'];
							$eventRate->cost                   = $validatedData['cost'];
							$eventRate->reserved_currency_cost = $validatedData['reserved_currency_cost'];
							$eventRate->status                 = $validatedData['status'];
							$eventRate->save();

							return redirect()->route('event.rate.datatable', $eventId)->with('success', 'Event rate updated successfully.');
						}
					catch(\Exception $exception)
						{
							return redirect()->route('event.rate.datatable', $eventId)->with('fail', $exception->getMessage());
						}


				}

		// Remove the specified event rate from storage
			public function destroy($eventId,$eventRateId)
				{
					$eventRate = ContentRate::find($eventRateId);
					$eventRate->delete();

					return redirect()->route('event.rates.datatable')->with('success', 'Event rate deleted successfully.');
				}

		/**
		 * Custom method added for datatable.
		 *
		 * @return \Illuminate\Http\JsonResponse
		 */
			public function datatable($event, Request $request, EventRateDatatable $datatable)
				{
					$datatable->columns = [0 => 'id', 1 => 'name', 2 => 'cost', 3 => 'reserved_currency_cost', 4 => 'date_from', 5 => 'date_to', 8 => 'status'];
					return response()->json($datatable->data($request, $event));
				}

		}
