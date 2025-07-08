@extends('layouts.app') {{-- Sesuaikan sama layoutmu --}}

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Daftar Produk</h2>

    <div class="row g-4">
        @foreach($products as $product)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card shadow-sm h-100">
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="object-fit: cover; height: 200px;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($product->description, 60) }}</p>
                        
                        {{-- Harga dan Stok sejajar --}}
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            <span class="badge bg-success">Stok: {{ $product->stock }}</span>
                        </div>
                    
                        <a href="#" class="btn btn-primary mt-auto">Lihat Detail</a>
                    </div>
                    
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
