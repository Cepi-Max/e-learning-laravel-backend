@extends('layouts.app') {{-- Sesuaikan dengan layout yang kamu pakai --}}

@section('content')
<div class="container mt-5">
    <a href="{{ route('admin.products.create') }}" class="btn btn-md bg-dark text-light link-light">Tambah Produk</a>
    <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Nama</th>
            <th scope="col">Deskripsi</th>
            <th scope="col">Harga</th>
            <th scope="col">Stok</th>
          </tr>
        </thead>
        <tbody>
            @php
                $index = 1;
            @endphp
            @foreach ($products as $p)
                <tr>
                <th scope="row">{{ $index++ }}</th>
                <td>{{ $p->name }}</td>
                <td>{{ $p->description }}</td>
                <td>Rp. {{ $p->price }}</td>
                <td>{{ $p->stock }}</td>
                </tr>
            @endforeach
        </tbody>
      </table>
</div>
@endsection
