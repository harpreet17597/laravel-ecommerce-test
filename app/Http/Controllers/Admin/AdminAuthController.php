<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminLoginRequest;
use App\Models\Admin;
use Hash;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Lang;
use Illuminate\Http\Request;

class AdminAuthController extends Controller
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
    public function login(AdminLoginRequest $request)
    {
        try {
            $adminQuery = Admin::Query();
            $adminQuery = $adminQuery->where('username', $request->username);
            $admin = $adminQuery->first();
            if ($admin) {
                // Check if the provided password matches the hashed password
                if (Hash::check($request->password, $admin->password)) {
                    // login admin
                    $returnArr = [];
                    $token = $admin->createToken('admin-api-skeleton')->plainTextToken;
                    $returnArr['token'] = $token;
                    return $this->returnSuccessResponse("Admin Login", $returnArr);
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
        $userObj->save();
        return $this->returnSuccessResponse(Lang::get('message.PROFILE_LOGOUT'));
    }
}
