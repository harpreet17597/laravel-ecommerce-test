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
            $products = Product::all();
            return $this->returnSuccessResponse("All products", $products);
        } catch (Exception $exception) {
            report($exception);
            return $this->returnErrorResponse($exception->getMessage());
        }
    }
}
