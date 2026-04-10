<?php

    namespace App\Http\Controllers\Backend;

    use App\Http\Controllers\Controller;
    use App\Http\Datatables\StreamDatatable;
    use App\Http\Datatables\TvDatatable;
    use App\Http\Requests\StoreTvRequest;
    use App\Http\Requests\UpdateTvRequest;
    use App\Models\Category;
    use App\Models\Content;
    use App\Models\Language;
    use App\Models\Region;
    use App\Traits\Meta;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Log;

    class TvController extends Controller
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
                    return view('Backend.modules.tv.index', $this->data);

                }

        /**
         * Show the form for creating a new resource.
         */
            public function create()
                {
                    $this->data['regions']    = Region::get();
                    $this->data['languages']  = Language::get();
                    $this->data['categories'] = Category::get();
                    return view('Backend.modules.tv.add', $this->data);
                }

        /**
         * Store a newly created resource in storage.
         */
            public function store(StoreTvRequest $request)
                {
                    $valid = $request->validated();
                    try
                        {
                            if (Content::create($valid))
                                {
                                    return self::success('TV', "TV created successfully", route('backend.tv.index'));
                                }
                            return self::failed('TV', "TV failed to create", route('backend.tv.index'));
                        }
                    catch (\Exception $e)
                        {
                            Log::error('TV Update', [$e->getMessage(), $e->getTrace()]);
                            return self::failed('TV', "TV failed to update", route('backend.tv.index'));
                        }
                }

        /**
         * Display the specified resource.
         */
            public function show(Content $tv)
                {
                    $this->data['tv'] = $tv;
                    return view('Backend.modules.tv.show', $this->data);
                }

        /**
         * Show the form for editing the specified resource.
         */
            public function edit(Content $tv)
                {
                    $this->data['tv']         = $tv;
                    $this->data['regions']    = Region::get();
                    $this->data['languages']  = Language::get();
                    $this->data['categories'] = Category::get();
                    return view('Backend.modules.tv.edit', $this->data);
                }

        /**
         * Update the specified resource in storage.
         */
            public function update(UpdateTvRequest $request, Content $tv)
                {
                    $valid = $request->validated();
                    try
                        {
                            if ($tv->update($valid))
                                {
                                    return self::success('TV', "TV updated successfully", route('backend.tv.index'));

                                }
                            return self::failed('TV', "TV failed to update", route('backend.tv.index'));
                        }
                    catch (\Exception $e)
                        {
                            Log::error('TV Update', [$e->getMessage(), $e->getTrace()]);
                            return self::failed('TV', "TV failed to update", route('backend.tv.index'));
                        }
                }

        /**
         * Remove the specified resource from storage.
         */
            public function destroy(Content $tv)
                {
                    try
                        {
                            if ($tv->delete())
                                {
                                    return self::success('TV', "TV deleted successfully", route('backend.tv.index'));
                                }
                            return self::failed('TV', "TV failed to delete", route('backend.tv.index'));
                        }
                    catch (\Exception $e)
                        {
                            Log::error('TV Delete', [$e->getMessage(), $e->getStackTrace()]);
                            return self::failed('TV', "TV failed to delete", route('backend.tv.index'));
                        }
                }


        /**
         * Custom method added for datatable.
         *
         * @return \Illuminate\Http\JsonResponse
         */
            public function datatable(Request $request, TvDatatable $datatable)
                {
                    $datatable->columns = [0 => 'uuid', 1 => 'title', 2 => "description"];
                    return response()->json($datatable->data($request));
                }
        }
