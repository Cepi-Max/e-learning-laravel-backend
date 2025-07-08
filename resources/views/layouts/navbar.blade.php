@php
    use Illuminate\Support\Facades\Auth;
    $user = Auth::user();
@endphp

<div class="topbar d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center px-4 py-3 mb-4 flex-wrap gap-3"
    style="background: #ffffff; border-radius: 12px; box-shadow: 0 2px 10px rgba(107, 175, 84, 0.08);">

    <!-- Kiri (Salam) -->
    <div class="text-start text-md-left">
        <h5 class="mb-1" style="font-weight: 600; color: #4c8d3d;">
            👋 Selamat Datang, {{ $user ? $user->name : 'Admin' }}
        </h5>
        <small style="color: #7c9f7c;">Semoga harimu menyenangkan 🌾</small>
    </div>

    <!-- Kanan (Profil + Logout) -->
    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-3 ms-auto">

        <div class="d-flex align-items-center gap-2">
            <img src="https://img.icons8.com/ios-glyphs/36/6baf54/user-male-circle.png" alt="Admin" width="32"
                height="32" style="border-radius: 50%;">
            <div style="line-height: 1;">
                <div style="font-weight: 500; font-size: 0.95rem; color: #2c3e50;">
                    {{ $user ? $user->email : '-' }}
                </div>
                <small style="color: #9e9e9e;">{{ ucfirst($user->role ?? 'Admin') }}</small>
            </div>
        </div>

        <form action="{{ route('logout') }}" method="GET" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm"
                style="background:#f5fdf6; color:#4c8d3d; border-radius:20px; font-weight:500; padding:6px 16px; border: 1px solid #d0ecd3; transition: all .2s;">
                <i class="fas fa-sign-out-alt me-1"></i> Logout
            </button>
        </form>

    </div>
</div>