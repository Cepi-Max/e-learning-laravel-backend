<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    //
    function index()
    { 
        $data = [
            'title' => 'Halaman Dashboard'
        ];
        return view('admin.dashboard.index', $data);
    }
    function superadmin()
    {
        $data = [
            'title' => 'Halaman Dashboard'
        ];  
        return view('admin', $data);
    }
    function admin()
    {
        $data = [
            'title' => 'Halaman Dashboard'
        ];  
        return view('admin', $data);
    }
    function petani()
    {
        $data = [
            'title' => 'Halaman Dashboard'
        ];  
        return view('admin', $data);
    }
    function pembeli()
    {
        $data = [
            'title' => 'Halaman Dashboard'
        ];  
        return view('admin', $data);
    }
}
