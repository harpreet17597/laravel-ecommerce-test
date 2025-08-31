<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminProductRequest;
use App\Http\Requests\Admin\AdminProductUpdateRequest;
use App\Models\Product;
use App\Traits\ApiResponser;
use Exception;

class AdminProductController extends Controller
{
    use ApiResponser;

    public function __construct() {}

    public function create(AdminProductRequest $request)
    {
        try {
            $product = Product::create([
                'name'        => $request->name,
                'description' => $request->description,
                'price'       => $request->price,
                'status'      => Product::AVAILABLE_PRODUCT
            ]);
            return $this->returnSuccessResponse("Product created", $product);
        } catch (Exception $exception) {
            report($exception);
            return $this->returnErrorResponse($exception->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $product = Product::find($id);
            if (!$product) {
                return $this->returnErrorResponse('Product not found!');
            }
            return $this->returnSuccessResponse("Product detail", $product);
        } catch (Exception $exception) {
            report($exception);
            return $this->returnErrorResponse($exception->getMessage());
        }
    }

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

    public function update(AdminProductUpdateRequest $request, $id)
    {
        try {
            $product = Product::find($id);
            if (!$product) {
                return $this->returnErrorResponse('Product not found!');
            }

            if ($request->has('name')) {
                $product->name = $request->name;
            }
            if ($request->has('description')) {
                $product->description = $request->description;
            }
            if ($request->has('price')) {
                $product->price = $request->price;
            }

            $product->save();

            return $this->returnSuccessResponse("Product updated", $product);
        } catch (Exception $exception) {
            report($exception);
            return $this->returnErrorResponse($exception->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $product = Product::find($id);
            if (!$product) {
                return $this->returnErrorResponse('Product not found!');
            }
            $product->delete();

            return $this->returnSuccessResponse("Product deleted successfully", $product);
        } catch (Exception $exception) {
            report($exception);
            return $this->returnErrorResponse($exception->getMessage());
        }
    }
}
