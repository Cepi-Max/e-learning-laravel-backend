@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="card shadow-lg border-0" style="border-radius: 1rem;">
            {{-- Card Header --}}
            <div class="card-header bg-white pb-0 pt-4 px-4 border-0">
                <div class="d-flex flex-column">
                    <h4 class="mb-1 fw-bolder" style="color:#6baf54;">
                        <i class="bi bi-people-fill me-2"></i>Database Pengguna
                    </h4>
                    <p class="text-muted mb-0">Kelola dan pantau semua akun pengguna yang terdaftar dalam sistem.</p>
                </div>
                <hr class="my-3">
            </div>

            <div class="card-body px-4 pt-0">
                {{-- Notifikasi --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if($errors->any())
                     <div class="alert alert-danger alert-dismissible fade show" role="alert">
                         <i class="bi bi-exclamation-triangle-fill me-2"></i>
                         <strong>Terjadi Kesalahan!</strong> Harap periksa kembali isian formulir Anda.
                         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                     </div>
                @endif

                {{-- Fitur Pencarian, Filter, dan Tombol Aksi --}}
                <div class="row mb-4 g-3 align-items-center">
                    <div class="col-md-6">
                        <form action="{{ route('admin.users.index') }}" method="GET">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" class="form-control form-control-lg bg-light border-0"
                                       placeholder="Cari pengguna berdasarkan nama atau email..." value="{{ request('search') }}">
                            </div>
                        </form>
                    </div>
                    <div class="col-md-3">
                        <form action="{{ route('admin.users.index') }}" method="GET" id="filterRoleForm">
                            <select name="role" class="form-select form-select-lg bg-light border-0"
                                    onchange="document.getElementById('filterRoleForm').submit();">
                                <option value="">Semua Role</option>
                                <option value="admin" @if(request('role') == 'admin') selected @endif>Admin</option>
                                <option value="petani" @if(request('role') == 'petani') selected @endif>Petani</option>
                                <option value="pembeli" @if(request('role') == 'pembeli') selected @endif>Pembeli</option>
                            </select>
                        </form>
                    </div>
                    <div class="col-md-3 text-end">
                        <button type="button" class="btn btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#addUserModal">
                            <i class="bi bi-plus-circle-fill me-2"></i>Tambah Pengguna
                        </button>
                    </div>
                </div>

                {{-- Tabel Pengguna --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="text-uppercase small text-muted">
                            <tr>
                                <th class="text-center" style="width:5%;">#</th>
                                <th class="text-start ps-3" colspan="2">Pengguna</th>
                                <th class="text-start">Lokasi</th>
                                <th class="text-center" style="width:12%;">Role</th>
                                <th class="text-center" style="width:18%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $index => $user)
                                <tr class="table-row-hover">
                                    <td class="text-center fw-bold text-muted">{{ $users->firstItem() + $index }}</td>
                                    <td style="width: 60px;">
                                        <img src="{{ $user->foto_profil ?? 'https://placehold.co/60x60/6baf54/white?text=User' }}"
                                             alt="Foto {{ $user->name }}" class="rounded-circle" width="45" height="45" style="object-fit: cover;">
                                    </td>
                                    <td class="ps-0">
                                        <div class="fw-semibold">{{ $user->name }}</div>
                                        <div class="small text-muted"><i class="bi bi-envelope-fill me-1"></i>{{ $user->email }}</div>
                                    </td>
                                    <td>
                                        <i class="bi bi-geo-alt-fill text-muted me-2"></i>{{ $user->lokasi ?? 'Tidak diketahui' }}
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $badge = match ($user->role) {
                                                'admin' => 'danger',
                                                'petani' => 'success',
                                                'pembeli' => 'warning',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge fw-semibold rounded-pill text-bg-{{ $badge }} px-3 py-2" style="font-size: 0.75rem;">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary fw-bold">
                                                <i class="bi bi-pencil-square me-1"></i> Kelola
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger fw-bold"
                                                    data-bs-toggle="modal" data-bs-target="#deleteUserModal"
                                                    data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">
                                                <i class="bi bi-trash-fill me-1"></i> Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="bi bi-search-heart fs-1 d-block mb-3 text-muted"></i>
                                        <h5 class="fw-bold">Data Tidak Ditemukan</h5>
                                        <p class="text-muted">Tidak ada pengguna yang cocok dengan kriteria pencarian atau filter Anda.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($users->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- MODAL: TAMBAH PENGGUNA BARU -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 1rem;">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bolder" id="addUserModalLabel" style="color:#6baf54;">
                           <i class="bi bi-person-plus-fill me-2"></i>Formulir Pengguna Baru
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-semibold">Alamat Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                             <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>
                        
                        {{-- KOLOM BARU DITAMBAHKAN DI SINI --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="lokasi" class="form-label fw-semibold">Lokasi</label>
                                <input type="text" class="form-control @error('lokasi') is-invalid @enderror" id="lokasi" name="lokasi" value="{{ old('lokasi') }}">
                                @error('lokasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone_number" class="form-label fw-semibold">Nomor Telepon</label>
                                <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" placeholder="Contoh: 08123456789">
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                             <label for="role" class="form-label fw-semibold">Role Pengguna</label>
                             <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                 <option value="" disabled selected>-- Pilih Role --</option>
                                 <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                 <option value="petani" {{ old('role') == 'petani' ? 'selected' : '' }}>Petani</option>
                                 <option value="pembeli" {{ old('role') == 'pembeli' ? 'selected' : '' }}>Pembeli</option>
                             </select>
                             @error('role')
                                 <div class="invalid-feedback">{{ $message }}</div>
                             @enderror
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success fw-bold">
                            <i class="bi bi-save-fill me-2"></i>Simpan Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: KONFIRMASI HAPUS PENGGUNA -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 1rem;">
                <form id="deleteUserForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bolder text-danger" id="deleteUserModalLabel">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Hapus Pengguna
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-4">
                        <p>Apakah Anda benar-benar yakin ingin menghapus pengguna <strong id="userNameToDelete" class="text-dark"></strong>? Tindakan ini tidak dapat diurungkan.</p>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger fw-bold">
                            <i class="bi bi-trash-fill me-2"></i>Ya, Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <style>
        .table-row-hover:hover { background-color: #f8f9fa; }
        .btn-outline-primary { color: #6baf54; border-color: #6baf54; }
        .btn-outline-primary:hover { color: #fff; background-color: #6baf54; border-color: #6baf54; }
    </style>
@endsection

@push('scripts')
<script>
    // Jika ada error validasi pada form tambah, modal akan otomatis terbuka kembali.
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            var addUserModal = new bootstrap.Modal(document.getElementById('addUserModal'));
            addUserModal.show();
        });
    @endif

    // Script untuk modal konfirmasi hapus.
    document.addEventListener('DOMContentLoaded', function() {
        const deleteUserModalEl = document.getElementById('deleteUserModal');
        if (deleteUserModalEl) {
            deleteUserModalEl.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const userId = button.getAttribute('data-user-id');
                const userName = button.getAttribute('data-user-name');
                const modalBodyUserName = deleteUserModalEl.querySelector('#userNameToDelete');
                const deleteForm = deleteUserModalEl.querySelector('#deleteUserForm');
                
                modalBodyUserName.textContent = userName;
                let actionUrl = "{{ url('admin/users') }}/" + userId;
                deleteForm.setAttribute('action', actionUrl);
            });
        }
    });
</script>
@endpush
