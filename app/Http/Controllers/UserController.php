<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * @param CreateUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(CreateUserRequest $request)
    {
        try {
            $user = new User();
            $user->fill($request->toArray());
            $user->saveOrFail();

            return response()->json([
                'success' => true,
                'data' => [
                    'api_key' => $user->getApiKey(),
                    'api_secret' => $user->getApiSecret()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($user = Auth::attempt($credentials)) {
            return response()->json([
                'success' => true,
                'data' => [
                    'api_key' => $request->user()->getApiKey(),
                    'api_secret' => $request->user()->getApiSecret()
                ]
            ]);
        }

        return response()->json(['success' => false], 404);
    }
}
