<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponser
{
    protected function successResponse($data, $code)
    {
        return response()->json($data, $code);
    }

    protected function errorResponse($data, $code = 400)
    {
        return response()->json($data, $code);
    }

    function returnSuccessResponse($message = '', $data = array(), $status_code = 200)
    {
        $is_array = !empty ($is_array) ? [] : (object) [];
        $returnArr = [
            'status' => true, //'success'
            'status_code' => $status_code,
            'message' => $message,
            'data' => $data
        ];
        return response()->json($returnArr, 200);
    }

    function returnErrorResponse($message = '', $data = array(), $code = 400)
    {
        $returnArr = [
            'status' => false, //'error'
            'message' => $message,
            'data' => ($data) ? ($data) : ((object) $data)
        ];
        return response()->json($returnArr, $code); //500
    }

    protected function paginate(Collection $collection)
    {
        $rules = [
            'per_page' => 'integer|min:2|max:50',
        ];

        Validator::validate(request()->all(), $rules);

        $page = LengthAwarePaginator::resolveCurrentPage();

        $perPage = 6;
        if (request()->has('per_page')) {
            $perPage = (int) request()->per_page;
        }

        $results = $collection->slice(($page - 1) * $perPage, $perPage)->values();

        $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);

        $paginated->appends(request()->all());

        return $paginated;
    }
}