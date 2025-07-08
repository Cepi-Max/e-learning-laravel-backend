<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;

// class SesiController extends Controller
// {
//     //
//     function index()
//     {
//         return view('login');
//     }

//     function login(Request $request)
//     {
//         $request->validate([
//             'email' => 'required',
//             'password' => 'required',
//         ],[
//             'email.required' => 'email harus diisi',
//             'password.required' => 'password harus diisi',
//         ]);

//         $infologin = [
//             'email' => $request->email,
//             'password' => $request->password,
//         ];

//         if(Auth::attempt($infologin)){
//             if(Auth::user()->role == 'superadmin'){
//                 return redirect('admin/superadmin');
//             } else if(Auth::user()->role == 'admin'){
//                 return redirect('admin/admin');
//             } else if(Auth::user()->role == 'petani'){
//                 return redirect('admin/petani');
//             } else if(Auth::user()->role == 'pembeli'){
//                 return redirect('admin/pembeli');
//             }
//         } else{
//             return redirect('')->withErrors('Username dan password yang dimasukkan tidak sesuai')->withInput();
//         }
//     }

//     function logout()
//     {
//         Auth::logout();
//         return redirect('');
//     }
// }