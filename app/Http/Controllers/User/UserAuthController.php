<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserLoginRequest;
use App\Models\Cart;
use App\Models\User;
use Hash;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Lang;
use Illuminate\Http\Request;

class UserAuthController extends Controller
{
    use ApiResponser;

    public function __construct()
    {
        $this->middleware('guest')->except(['logout']);
    }

    /**
     * **************************************************************
     * LOGIN
     * **************************************************************
     * */
    public function login(UserLoginRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            if ($user) {
                // Check if the provided password matches the hashed password
                if (Hash::check($request->password, $user->password)) {
                    $token = $user->createToken('user-api-skeleton')->plainTextToken;

                    // 🔹 Move session cart to DB if exists
                    $sessionCart = session()->get('cart', []);
                    if (!empty($sessionCart)) {
                        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

                        foreach ($sessionCart as $item) {
                            $cartItem = $cart->items()->where('product_id', $item['product_id'])->first();
                            if ($cartItem) {
                                // update existing item quantity
                                $cartItem->increment('quantity', $item['quantity']);
                            } else {
                                // create new item
                                $cart->items()->create([
                                    'product_id' => $item['product_id'],
                                    'quantity'   => $item['quantity'],
                                ]);
                            }
                        }

                        // 🔹 Clear session cart after merging
                        session()->forget('cart');
                    }

                    return $this->returnSuccessResponse("User Login", [
                        'token' => $token,
                    ]);
                }
            }

            throw new Exception('Credentials not matched');
        } catch (Exception $exception) {
            report($exception);
            return $this->returnErrorResponse($exception->getMessage());
        }
    }

    /**
     * **************************************************************
     * LOGOUT
     * **************************************************************
     * */
    public function logout(Request $request)
    {
        $userObj = $request->user();

        if (!$userObj) {
            return $this->returnErrorResponse(Lang::get('message.NOT_AUTHORIZED'));
        }

        auth()->guard('web')->logout();
        $userObj->tokens()->delete();

        return $this->returnSuccessResponse(Lang::get('message.PROFILE_LOGOUT'));
    }
}
