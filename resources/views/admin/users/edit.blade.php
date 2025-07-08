@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">
                <div class="card shadow-lg rounded-4 border-0">
                    <div class="card-header bg-white border-0 pb-0 pt-4 px-4">
                        <h5 class="text-success fw-bold mb-0">
                            <i class="bi bi-person-gear me-2"></i>Ubah Data Pengguna
                        </h5>
                         <p class="text-muted mt-1">Perbarui informasi pengguna di bawah ini.</p>
                    </div>

                    <div class="card-body px-4">
                        <form id="editUserForm" action="{{ route('admin.users.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="text-center mb-4">
                                <img src="{{ $user->foto_profil ?? 'https://placehold.co/80x80/6baf54/white?text=' . substr($user->name, 0, 1) }}"
                                     alt="Foto Profil" width="80" height="80"
                                     class="rounded-circle shadow-sm border border-2 border-success">
                            </div>

                            {{-- Form Fields --}}
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label fw-semibold">Alamat Email</label>
                                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                     @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                             <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="lokasi" class="form-label fw-semibold">Lokasi</label>
                                    <input type="text" name="lokasi" id="lokasi" class="form-control @error('lokasi') is-invalid @enderror" value="{{ old('lokasi', $user->lokasi) }}">
                                     @error('lokasi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone_number" class="form-label fw-semibold">Nomor Telepon</label>
                                    <input type="text" name="phone_number" id="phone_number" class="form-control @error('phone_number') is-invalid @enderror" value="{{ old('phone_number', $user->phone_number) }}">
                                     @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="role" class="form-label fw-semibold">Peran (Role)</label>
                                <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role }}" {{ old('role', $user->role) == $role ? 'selected' : '' }}>
                                            {{ ucfirst($role) }}
                                        </option>
                                    @endforeach
                                </select>
                                 @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-4">

                            <p class="text-muted small">Kosongkan kolom di bawah jika Anda tidak ingin mengubah password pengguna.</p>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label fw-semibold">Password Baru</label>
                                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                                     @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                 <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password Baru</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary rounded-3 px-4">
                                    <i class="bi bi-arrow-left-circle me-1"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-success rounded-3 px-4">
                                    <i class="bi bi-save2 me-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
@endpush
