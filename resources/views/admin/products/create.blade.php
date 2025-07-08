@extends('layouts.app') {{-- Sesuaikan dengan layout yang kamu pakai --}}

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white rounded-top-4">
            <h4 class="mb-0">Tambah Produk Baru</h4>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Nama Produk</label>
                    <input type="text" name="name" class="form-control border-dark rounded-3" id="name" placeholder="Masukkan nama produk" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Deskripsi Produk</label>
                    <textarea name="description" class="form-control rounded-3" id="description" rows="4" placeholder="Deskripsi produk"></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label fw-semibold">Harga (Rp)</label>
                        <input type="number" step="0.01" name="price" class="form-control rounded-3" id="price" placeholder="Masukkan harga" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="stock" class="form-label fw-semibold">Stok</label>
                        <input type="number" name="stock" class="form-control rounded-3" id="stock" placeholder="Jumlah stok" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label fw-semibold">Gambar Produk</label>
                    <input type="file" name="image" class="form-control rounded-3" id="image" accept="image/*">
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary rounded-3">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
