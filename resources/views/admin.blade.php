@extends('layouts.app')

@section('content')
  <div class="bg-white container-sm col-6 border my-3 rounded px-5 py-3 pb-5">
    <h1>Halo!!</h1>
    <div>Selamat datang di halaman admin</div>
    <div><a href="/logout" class="btn btn-sm btn-secondary">Logout >></a></div>
    <div class="card mt-3">
      <ul class="list-group list-group-flush">
        @if (Auth::user()->role == 'superadmin')
        <li class="list-group-item">Menu superadmin</li>
        @endif
        @if (Auth::user()->role == 'admin')
        <li class="list-group-item">Menu admin</li>
        @endif
        @if (Auth::user()->role == 'petani')
        <li class="list-group-item">Menu petani</li>
        @endif
        @if (Auth::user()->role == 'pembeli')
        <li class="list-group-item">Menu pembeli</li>
        @endif
      </ul>
    </div>
  </div>

@endsection