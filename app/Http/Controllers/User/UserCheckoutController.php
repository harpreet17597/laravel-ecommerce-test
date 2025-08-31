<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Traits\ApiResponser;

class UserCheckoutController extends Controller
{
    use ApiResponser;
    public function checkout(Request $request)
    {
        try {
            $user = $request->user(); // from Sanctum

            // Fetch cart of user
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);
            $cartItems = $cart->items()->with('product')->get();

            if ($cartItems->isEmpty()) {
                return $this->returnErrorResponse('Cart is empty');
            }

            DB::beginTransaction();

            $total = 0;
            foreach ($cartItems as $item) {
                $total += $item->product->price * $item->quantity;
            }

            $order = Order::create([
                'user_id' => $user->id,
                'total'   => $total,
                'status'  => 'pending',
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'price'      => $item->product->price,
                ]);
            }

            // clear DB cart
            $cart->items()->delete();

            DB::commit();

            return $this->returnSuccessResponse("Order placed successfully!", $order->load('items.product'));
        } catch (Exception $exception) {
            DB::rollBack();
            report($exception);
            return $this->returnErrorResponse($exception->getMessage());
        }
    }
}
