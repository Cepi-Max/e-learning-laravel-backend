<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    //
    function index()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            /** @var \App\Models\User $user */
            if ($request->expectsJson() || $request->wantsJson() || $request->is('api/*')) {
                
                $token = $user->createToken('mobile_token')->plainTextToken;

                return response()->json([
                    'message' => 'Login berhasil',
                    'role' => $user->role,
                    'token' => $token,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'lokasi' => $user->lokasi,
                        'role' => $user->role,
                    ]
                ], 200);
            }

            switch ($user->role) {
                case 'superadmin':
                    return redirect('admin/superadmin');
                case 'admin':
                    return redirect('/dashboard');
                case 'petani':
                    return redirect('admin/petani');
                case 'pembeli':
                    return redirect('admin/pembeli');
                default:
                    Auth::logout(); 
                    return redirect('/login')->withErrors('Role tidak dikenali');
            }
        } else {
            if ($request->expectsJson() || $request->wantsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Email atau password salah'], 401);
            }

            return redirect('/login')
                ->withErrors('Email atau password salah')
                ->withInput();
        }
    }

    function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'lokasi' => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson() || $request->wantsJson() || $request->is('api/*')) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'lokasi' => $request->lokasi,
        ]);

        if ($request->expectsJson() || $request->wantsJson() || $request->is('api/*')) {
            $token = $user->createToken('mobile_token')->plainTextToken;
            return response()->json([
                'message' => 'Registrasi berhasil',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'lokasi' => $user->lokasi,
                ]
            ], 201);
        }

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }


    public function logout(Request $request)
    {
        if ($request->expectsJson() || $request->wantsJson() || $request->is('api/*')) {
            // Hapus token Sanctum
            $request->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'Logout berhasil'], 200);
        }

        // Logout Web biasa
        Auth::logout();
        return redirect('/login')->with('message', 'Logout berhasil');
    }
}
