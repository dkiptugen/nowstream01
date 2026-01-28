<?php

	namespace App\Http\Controllers\Backend;

	use App\Http\Controllers\Controller;
	use App\Http\Datatables\ChannelStreamDatatable;
	use App\Models\Channel;
	use Illuminate\Http\Request;

	class StreamController extends Controller
		{
		/**
		 * Display a listing of the resource.
		 */
			public function index($channel)
				{
					$this->data['channel'] = Channel::whereIdentifier($channel)
					                                ->first();
					return view('Backend.modules.channel_streams.index', $this->data);

				}

		/**
		 * Show the form for creating a new resource.
		 */
			public function create()
				{
					//
				}

		/**
		 * Store a newly created resource in storage.
		 */
			public function store(Request $request)
				{
					//
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
		 */
			public function edit(string $id)
				{
					//
				}

		/**
		 * Update the specified resource in storage.
		 */
			public function update(Request $request, string $id)
				{
					//
				}

		/**
		 * Remove the specified resource from storage.
		 */
			public function destroy(string $id)
				{
					//
				}


		/**
		 * Custom method added for datatable.
		 *
		 * @return \Illuminate\Http\JsonResponse
		 */
			public function datatable($channelId,Request $request, ChannelStreamDatatable $datatable)
				{


					$datatable->columns = [0 => 'id',1=>'title',2=>"description",7=>'start_time',8=>'end_time',9=>'ended'];
					return response()->json($datatable->data($request,$channelId));
				}

		}
