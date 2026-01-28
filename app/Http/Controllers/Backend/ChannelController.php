<?php

	namespace App\Http\Controllers\Backend;

	use App\Http\Controllers\Controller;
	use App\Http\Datatables\ChannelDatatable;
	use App\Http\Requests\StoreChannel;
	use App\Http\Requests\UpdateChannel;
	use App\Http\Services\UploadService;
	use App\Models\Channel;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Log;
	use Illuminate\Support\Facades\Storage;

	class ChannelController extends Controller
		{
			public function __construct()
				{
					parent::__construct();

				}

		/**
		 * Display a listing of the resource.
		 */
			public function index()
				{
					$this->data['title'] = 'Channels : ' . $this->data['title'];
					return view('Backend.modules.channel.index', $this->data);
				}

		/**
		 * Show the form for creating a new resource.
		 */
			public function create()
				{
					$this->data['title'] = 'Add Channel : ' . $this->data['title'];
					return view('Backend.modules.channel.add', $this->data);
				}

		/**
		 * Store a newly created resource in storage.
		 */
			public function store(StoreChannel $request)
				{
					try
						{
							$validated = $request->validated();
							if ($validated)
								{

									$channel             = new Channel();
									$channel->identifier = self::identifer('Channel', 'identifier');
									$channel->name       = $request->channel_name;
									if ($request->hasFile('thumbnail'))
										{
											$image              = new UploadService();
											$upload             = $image->file_upload($request, 'thumbnail', 'channel_thumbnail', 'public_2');
											$channel->thumbnail = $upload['path'];

										}
									if ($request->hasFile('cover_image'))
										{
											$image                = new UploadService();
											$upload               = $image->file_upload($request, 'cover_image', 'channel_cover', 'public_2');
											$channel->cover_image = $upload['path'];

										}
									$channel->description       = $request->description;
									$channel->status            = 1;
									$channel->stream_partner_id = 1;
									$res                        = $channel->save();
									if ($res)
										{
											return self::success('channel', 'Saved successfully', route('channel.index'));
										}
									return self::fail('channel', 'error encountered when saving, try again later', route('channel.index'));
								}
							else
								{
									return self::fail('channel', $validated, route('channel.index'));
								}
						}
					catch (\Exception $e)
						{
							Log::error($e->getMessage());
						}
				}

		/**
		 * Display the specified resource.
		 */
			public function show(Channel $channel)
				{
					//
				}

		/**
		 * Show the form for editing the specified resource.
		 */
			public function edit(Channel $channel)
				{
					$this->data['title']   = 'Channels : ' . $this->data['title'];
					$this->data['channel'] = $channel;
					return view('Backend.modules.channel.edit', $this->data);
				}

		/**
		 * Update the specified resource in storage.
		 */
			public function update(UpdateChannel $request, Channel $channel)
				{
					try
						{
							$validated = $request->validated();
							if ($validated)
								{
									$channel->identifier = self::identifer('Channel', 'identifier');
									$channel->name       = $request->channel_name;
									if ($request->hasFile('thumbnail'))
										{
											$image              = new UploadService();
											$upload             = $image->file_upload($request, 'thumbnail', 'channel_thumbnail', 'public_2');
											$channel->thumbnail = $upload['path'];

										}
									if ($request->hasFile('cover_image'))
										{
											$image                = new UploadService();
											$upload               = $image->file_upload($request, 'cover_image', 'channel_cover', 'public_2');
											$channel->cover_image = $upload['path'];

										}
									$channel->description       = $request->description;
									$channel->status            = 1;
									$channel->stream_partner_id = 1;
									$res                        = $channel->save();
									if ($res)
										{
											return self::success('channel', 'Saved successfully', route('channel.index'));
										}
									return self::fail('channel', 'error encountered when saving, try again later', route('channel.index'));
								}
							else
								{
									return self::fail('channel', $validated, route('channel.index'));
								}
						}
					catch (\Exception $e)
						{
							Log::error($e->getMessage());
						}
				}

		/**
		 * Remove the specified resource from storage.
		 */
			public function destroy(Channel $channel)
				{
					//
				}

			public function datatable(Request $request, ChannelDatatable $datatable)
				{
					$datatable->columns = [
						1 => 'name', 2 => 'thumbnail', 5 => 'status', 6 => 'created_at'
					];

					return response()->json($datatable->data($request));
				}
		}
