<?php

    namespace App\Http\Controllers\Backend;

    use App\Http\Controllers\Controller;
    use App\Http\Datatables\MicrositeDatatable;
    use App\Http\Requests\StoreMicrosite;
    use App\Http\Requests\UpdateMicrosite;
    use App\Models\Microsite;
    use App\Traits\Meta;
    use Illuminate\Http\Request;

    class MicrositeController extends Controller
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
                    return view('Backend.modules.microsite.index', $this->data);
                }

        /**
         * Show the form for creating a new resource.
         */
            public function create()
                {
                    return view('Backend.modules.microsite.add', $this->data);
                }

        /**
         * Store a newly created resource in storage.
         */
            public function store(StoreMicrosite $request)
                {
                    $validated = $request->validated();

                    try
                        {
                            $microsite = new Microsite();
                            $result    = $microsite->store($validated);

                            if ($result)
                                {
                                    return self::success(
                                        'Microsite',
                                        'Store successful',
                                        route('backend.microsite.index')
                                    );
                                }

                            return self::failed(
                                'Microsite',
                                'Store failed',
                                route('backend.microsite.index')
                            );

                        }
                    catch (\Throwable $e)
                        {
                            \Log::error('Microsite store error: ' . $e->getMessage());

                            return self::failed(
                                'Microsite',
                                'Something went wrong',
                                route('backend.microsite.index')
                            );
                        }
                }

        /**
         * Display the specified resource.
         */
            public function show(Microsite $microsite)
                {
                    //
                }

        /**
         * Show the form for editing the specified resource.
         */
            public function edit(Microsite $microsite)
                {
                    $this->data['microsite'] = $microsite;
                    return view('Backend.modules.microsite.edit', $this->data);
                }

        /**
         * Update the specified resource in storage.
         */
            public function update(UpdateMicrosite $request, Microsite $microsite)
                {
                    try
                        {
                            $updated = $microsite->update($request->validated());

                            if ($updated)
                                {
                                    return self::success(
                                        'Microsite',
                                        'Update successful',
                                        route('backend.microsite.index')
                                    );
                                }

                            return self::failed(
                                'Microsite',
                                'Update failed',
                                route('backend.microsite.index')
                            );

                        }
                    catch (\Throwable $e)
                        {
                            \Log::error('Microsite update error: ' . $e->getMessage());

                            return self::failed(
                                'Microsite',
                                'Something went wrong',
                                route('backend.microsite.index')
                            );
                        }
                }

        /**
         * Remove the specified resource from storage.
         */
            public function destroy(Microsite $microsite)
                {
                    //
                }
            public function datatable(Request $request, MicrositeDatatable $datatable)
                {
                    $datatable->columns = [0=>'id'];
                    return response()->json($datatable->data($request));
                }
        }
