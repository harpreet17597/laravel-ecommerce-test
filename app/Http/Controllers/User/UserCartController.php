<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Laravel\Sanctum\PersonalAccessToken;
use Exception;

class UserCartController extends Controller
{
    use ApiResponser;

    private function getAuthUser(Request $request)
    {
        $token = $request->bearerToken();
        if ($token) {
            $accessToken = PersonalAccessToken::findToken($token);
            if ($accessToken) {
                return $accessToken->tokenable; // User model
            }
        }
        return null;
    }

    /**
     * Cart items for both guest & logged-in users
     */
    private function getCartItems(Request $request)
    {
        $user = $this->getAuthUser($request);
        $cartItems = [];

        if ($user) {
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);

            foreach ($cart->items()->with('product')->get() as $item) {
                $cartItems[] = [
                    'product_id' => $item->product_id,
                    'name'       => $item->product->name,
                    'quantity'   => $item->quantity,
                ];
            }
        } else {
            $sessionCart = session()->get('cart', []);
            foreach ($sessionCart as $productId => $item) {
                $cartItems[] = [
                    'product_id' => $item['product_id'],
                    'name'       => $item['name'],
                    'quantity'   => $item['quantity'],
                ];
            }
        }

        return $cartItems;
    }

    public function index(Request $request)
    {
        try {
            return $this->returnSuccessResponse("Cart", $this->getCartItems($request));
        } catch (Exception $exception) {
            report($exception);
            return $this->returnErrorResponse($exception->getMessage());
        }
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1'
        ]);

        try {
            $user = $this->getAuthUser($request);

            if ($user) {
                $cart = Cart::firstOrCreate(['user_id' => $user->id]);
                $item = $cart->items()->where('product_id', $request->product_id)->first();

                if ($item) {
                    $item->increment('quantity', $request->quantity);
                } else {
                    $cart->items()->create([
                        'product_id' => $request->product_id,
                        'quantity'   => $request->quantity,
                    ]);
                }
            } else {
                $cart = session()->get('cart', []);
                if (isset($cart[$request->product_id])) {
                    $cart[$request->product_id]['quantity'] += $request->quantity;
                } else {
                    $product = Product::find($request->product_id);
                    $cart[$request->product_id] = [
                        'product_id' => $product->id,
                        'name'       => $product->name,
                        'quantity'   => $request->quantity
                    ];
                }
                session()->put('cart', $cart);
            }

            return $this->returnSuccessResponse("Item added to cart", $this->getCartItems($request));
        } catch (Exception $exception) {
            report($exception);
            return $this->returnErrorResponse($exception->getMessage());
        }
    }

    public function update(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            $user = $this->getAuthUser($request);
            if ($user) {
                $cart = Cart::firstOrCreate(['user_id' => $user->id]);
                $item = $cart->items()->where('product_id', $productId)->first();
                if (!$item) {
                    throw new Exception('Cart item not found!');
                }
                $item->update(['quantity' => $request->quantity]);
            } else {
                $cart = session()->get('cart', []);
                if (!isset($cart[$productId])) {
                    throw new Exception('Cart item not found!');
                }
                $cart[$productId]['quantity'] = $request->quantity;
                session()->put('cart', $cart);
            }

            return $this->returnSuccessResponse("Cart Updated", $this->getCartItems($request));
        } catch (Exception $exception) {
            report($exception);
            return $this->returnErrorResponse($exception->getMessage());
        }
    }

    public function remove(Request $request, $productId)
    {
        try {
            $user = $this->getAuthUser($request);
            if ($user) {
                $cart = Cart::firstOrCreate(['user_id' => $user->id]);
                $item = $cart->items()->where('product_id', $productId)->first();
                if (!$item) {
                    throw new Exception('Cart item not found!');
                }
                $item->delete();
            } else {
                $cart = session()->get('cart', []);
                if (!isset($cart[$productId])) {
                    throw new Exception('Cart item not found!');
                }
                unset($cart[$productId]);
                session()->put('cart', $cart);
            }

            return $this->returnSuccessResponse("Cart Item removed", $this->getCartItems($request));
        } catch (Exception $exception) {
            report($exception);
            return $this->returnErrorResponse($exception->getMessage());
        }
    }
}
