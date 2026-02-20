<?php

namespace App\Http\Datatables;

use App\Models\Microsite;
use Illuminate\Support\Facades\Cache;

class MicrositeDatatable
{
    public array $columns = [

    ];

    public function data($request): array
    {
        if (!$request->user()->can('view_Microsite')) {
            abort(403);
        }

        $limit  = (int) $request->input('length', 10);
        $start  = (int) $request->input('start', 0);
        $draw   = (int) $request->input('draw');

        $orderIndex = (int) $request->input('order.0.column', 0);
        $dir        = $request->input('order.0.dir') === 'asc' ? 'asc' : 'desc';
        $orderColumn = $this->columns[$orderIndex] ?? 'id';

        $cacheKey = "datatable:Microsite:" . md5(json_encode($request->all()));

        return Cache::remember($cacheKey, 60, function () use ($limit, $start, $draw, $orderColumn, $dir, $request) {

            $query = Microsite::query()
                ->with([]);

            $totalData = (clone $query)->count();

            if ($search = $request->input('search.value')) {
                $query->where(function ($query) use ($search) {

                });
            }

            $totalFiltered = (clone $query)->count();

            $rows = $query
                ->orderBy($orderColumn, $dir)
                ->offset($start)
                ->limit($limit)
                ->get();

            $data = [];

            foreach ($rows as $row) {
                $data[] = $row->toArray();
            }

            return [
                'draw' => $draw,
                'recordsTotal' => $totalData,
                'recordsFiltered' => $totalFiltered,
                'data' => $data,
            ];
        });
    }
}
