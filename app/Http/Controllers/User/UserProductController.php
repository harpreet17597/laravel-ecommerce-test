<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Traits\ApiResponser;
use Exception;

class UserProductController extends Controller
{
    use ApiResponser;

    public function __construct() {}

    public function index()
    {
        try {
            $query = Product::query();

            // Check if 'search' query param exists
            if ($search = request()->query('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            }

            $products = $query->get();

            return $this->returnSuccessResponse("All products", $products);
        } catch (Exception $exception) {
            report($exception);
            return $this->returnErrorResponse($exception->getMessage());
        }
    }
}
