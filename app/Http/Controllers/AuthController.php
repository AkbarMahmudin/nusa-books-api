<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request) {
        $rules = [
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'data'    => $validator->errors()
            ], 400);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $data['token'] = $user->createToken('MyApp')->plainTextToken;
        $data['name']  = $user->name;

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully.',
            'data'    => $data
        ]);
    }

    public function login(Request $request) {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $data['token'] = $user->createToken('MyApp')->plainTextToken;
            $data['name']  = $user->name;

            return response()->json([
                'success' => true,
                'message' => 'User logged in successfully.',
                'data'    => $data
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unauthorized.'
        ], 401);
    }

    public function logout() {
        Auth::user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'User logged out successfully.'
        ]);
    }
}
