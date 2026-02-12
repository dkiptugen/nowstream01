<?php

namespace App\Http\Datatables;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserDatatable
{
    protected array $columns = [
        0 => 'id',
        1 => 'name',
        2 => 'email',
        3 => 'email_verified_at',
        4 => 'status',
        5 => 'phone',
        6 => 'image',
        7 => 'password',
        8 => 'password_changed_at',
        9 => 'stream_auth',
        10 => 'verification_key',
        11 => 'remember_token',
        12 => 'created_at',
        13 => 'updated_at'
    ];

    public function data($request): array
    {
        if (!$request->user()->can('view_User')) {
            abort(403);
        }

        $limit  = (int) $request->input('length', 10);
        $start  = (int) $request->input('start', 0);
        $draw   = (int) $request->input('draw');

        $orderIndex = (int) $request->input('order.0.column', 0);
        $dir        = $request->input('order.0.dir') === 'asc' ? 'asc' : 'desc';
        $orderColumn = $this->columns[$orderIndex] ?? 'id';

        $cacheKey = "datatable:User:" . md5(json_encode($request->all()));

        return Cache::remember($cacheKey, 60, function () use ($limit, $start, $draw, $orderColumn, $dir, $request) {

            $query = User::query()
                ->with([]);

            $totalData = (clone $query)->count();

            if ($search = $request->input('search.value')) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('name', 'LIKE', "%{$search}%");
                $query->orWhere('email', 'LIKE', "%{$search}%");
                $query->orWhere('email_verified_at', 'LIKE', "%{$search}%");
                $query->orWhere('status', 'LIKE', "%{$search}%");
                $query->orWhere('phone', 'LIKE', "%{$search}%");
                $query->orWhere('image', 'LIKE', "%{$search}%");
                $query->orWhere('password', 'LIKE', "%{$search}%");
                $query->orWhere('password_changed_at', 'LIKE', "%{$search}%");
                $query->orWhere('stream_auth', 'LIKE', "%{$search}%");
                $query->orWhere('verification_key', 'LIKE', "%{$search}%");
                $query->orWhere('remember_token', 'LIKE', "%{$search}%");
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