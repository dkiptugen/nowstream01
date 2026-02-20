<?php

    namespace App\Http\Datatables;

    use App\Models\Microsite;
    use Illuminate\Support\Facades\Cache;

    class MicrositeDatatable
        {
            public array $columns
                = [

                ];

            public function data($request)
            : array
                {

                    $limit = (int)$request->input('length', 10);
                    $start = (int)$request->input('start', 0);
                    $draw  = (int)$request->input('draw');

                    $orderIndex  = (int)$request->input('order.0.column', 0);
                    $dir         = $request->input('order.0.dir') === 'asc' ? 'asc' : 'desc';
                    $orderColumn = $this->columns[$orderIndex] ?? 'id';

                    $cacheKey = "datatable:Microsite:" . md5(json_encode($request->all()));

                    return Cache::remember($cacheKey, 60, function () use ($limit, $start, $draw, $orderColumn, $dir, $request)
                        {

                            $query     = Microsite::query();
                            $totalData = (clone $query)->count();
                            if ($search = $request->input('search.value'))
                                {
                                    $query->where(function ($q) use ($search)
                                        {
                                            return $q->where('name', 'LIKE', "%{$search}%")
                                                     ->orWhere('domain', 'LIKE', "%{$search}%");
                                        });
                                }

                            $totalFiltered = (clone $query)->count();

                            $rows = $query
                                ->orderBy($orderColumn, $dir)
                                ->offset($start)
                                ->limit($limit)
                                ->get();

                            $data = [];
                            $pos  = $start + 1;
                            foreach ($rows as $row)
                                {
                                    $data[] = [
                                        "pos"          => $pos++,
                                        "name"         => $row->name,
                                        "domain"       => $row->domain,
                                        "banner"       => $row->banner,
                                        "cover"        => $row->cover,
                                        "description"  => $row->description,
                                        "keywords"     => $row->keywords,
                                        "social_links" => collect($row->social_links)->implode(','),
                                        "views"        => $row->views,
                                        "followers"    => $row->followers,
                                        "status"       => $row->status,
                                        "action"       => $this->button($row, $request),
                                    ];
                                    $pos++;
                                }

                            return [
                                'draw'            => $draw,
                                'recordsTotal'    => $totalData,
                                'recordsFiltered' => $totalFiltered,
                                'data'            => $data,
                            ];
                        });
                }

            private function button($row, $request)
                {
                    return "";
                }
        }
