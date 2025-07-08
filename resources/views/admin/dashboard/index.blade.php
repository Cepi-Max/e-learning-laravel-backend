@extends('layouts.app') {{-- Sesuaikan dengan layout utama Anda --}}

@section('content')
<div class="container-fluid">

    {{-- Bagian Header --}}
    <div class="mb-4">
        <h2 class="fw-bolder text-dark mb-1">Selamat Datang Kembali!</h2>
    </div>

    {{-- Bagian Filter Periode Data --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form id="filterForm" action="{{ route('show.dashboard') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-md-auto">
                    <label class="fw-bold text-muted"><i class="bi bi-funnel-fill me-2"></i>Ubah Periode Data:</label>
                </div>
                <div class="col-md-4">
                    <select name="bulan" class="form-select" onchange="document.getElementById('filterForm').submit();">
                        <option value="">Semua Bulan</option>
                        @foreach($listBulan as $key => $namaBulan)
                            <option value="{{ $key }}" {{ $bulanTerpilih == $key ? 'selected' : '' }}>
                                {{ $namaBulan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="tahun" class="form-select" onchange="document.getElementById('filterForm').submit();">
                        @foreach($listTahun as $tahun)
                            <option value="{{ $tahun }}" {{ $tahunTerpilih == $tahun ? 'selected' : '' }}>
                                {{ $tahun }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    {{-- Kartu Statistik (Stat Cards) --}}
    <div class="row g-4">
        {{-- Total Panen --}}
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">Total Panen</div>
                            <div class="h4 mb-0 fw-bolder text-gray-800">{{ number_format($totalPadi, 0, ',', '.') }} Kg</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-box-seam fs-1 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Petani Terdaftar --}}
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">Petani Terdaftar</div>
                            <div class="h4 mb-0 fw-bolder text-gray-800">{{ $users }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people-fill fs-1 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Total Transaksi --}}
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">Total Transaksi</div>
                            <div class="h4 mb-0 fw-bolder text-gray-800">{{ $totalPenjualan }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-receipt-cutoff fs-1 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Total Penghasilan --}}
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">Total Penghasilan</div>
                            <div class="h4 mb-0 fw-bolder text-gray-800">Rp {{ number_format($penghasilanDesa, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-cash-stack fs-1 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Baris untuk Grafik --}}
    <div class="row mt-4 g-4">
        {{-- Grafik Penjualan (Dinamis Harian/Bulanan) --}}
        <div class="col-lg-6">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-success"><i class="bi bi-graph-up me-2"></i>{{ $chartTitle }}</h6>
                </div>
                <div class="card-body d-flex align-items-center">
                    <canvas id="penjualanChart" style="height:320px; width:100%;"></canvas>
                </div>
            </div>
        </div>

        {{-- Produk Terlaris (Doughnut Chart) --}}
        <div class="col-lg-6">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary"><i class="bi bi-trophy-fill me-2"></i>Produk Terlaris</h6>
                </div>
                <div class="card-body d-flex align-items-center">
                    @if($labelsProduk->isNotEmpty())
                        <canvas id="produkTerlarisChart" style="height:320px; width:100%;"></canvas>
                    @else
                        <div class="w-100 text-center text-muted">
                            <i class="bi bi-trophy fs-1 d-block mb-2"></i>
                            <p class="fw-bold">Belum ada produk terlaris pada periode ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Grafik Penghasilan Bulanan (Bar Chart) --}}
        <div class="col-lg-12">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-warning"><i class="bi bi-bar-chart-line me-2"></i>Grafik Penghasilan Bulanan (Tahun {{ $tahunTerpilih }})</h6>
                </div>
                <div class="card-body d-flex align-items-center">
                    <canvas id="penghasilanDesaChart" style="height:320px; width:100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Data Stok Padi --}}
    <div class="card mt-4 shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 fw-bold text-success"><i class="bi bi-table me-2"></i>Data Stok Padi Terbaru</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">No</th>
                            <th>Nama Padi</th>
                            <th class="text-center">Jenis</th>
                            <th class="text-center">Stok (Kg)</th>
                            <th>Tanggal Diperbarui</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dataPadi as $index => $tp)
                            <tr>
                                <td class="text-center fw-bold">{{ $dataPadi->firstItem() + $index }}</td>
                                <td class="fw-semibold">{{ $tp->nama }}</td>
                                <td class="text-center">
                                    @php
                                        $badgeClass = match (strtolower($tp->jenis_padi)) {
                                            'organik' => 'text-bg-success',
                                            'non-organik' => 'text-bg-secondary',
                                            default => 'text-bg-light',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $tp->jenis_padi }}</span>
                                </td>
                                <td class="text-center">{{ number_format($tp->jumlah_padi, 0, ',', '.') }}</td>
                                <td>{{ \Carbon\Carbon::parse($tp->updated_at)->isoFormat('D MMMM YYYY, HH:mm') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="bi bi-cloud-drizzle fs-1 d-block mb-3"></i>
                                    <h5 class="fw-bold">Tidak ada data stok padi.</h5>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($dataPadi->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $dataPadi->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    :root {
        --bs-success-rgb: 107, 175, 84;
        --bs-primary-rgb: 78, 115, 223;
        --bs-info-rgb: 28, 200, 138;
        --bs-warning-rgb: 246, 194, 62;
        --gray-300: #dddfeb;
        --gray-800: #5a5c69;
    }
    .stat-card {
        border-left: 5px solid rgb(var(--bs-success-rgb));
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 .5rem 1.5rem rgba(0, 0, 0, .1) !important;
    }
    .col-xl-3:nth-child(2) .stat-card { border-color: rgb(var(--bs-primary-rgb)); }
    .col-xl-3:nth-child(3) .stat-card { border-color: rgb(var(--bs-info-rgb)); }
    .col-xl-3:nth-child(4) .stat-card { border-color: rgb(var(--bs-warning-rgb)); }
    .text-xs { font-size: .8rem; }
    .text-gray-300 { color: var(--gray-300) !important; }
    .text-gray-800 { color: var(--gray-800) !important; }
    .table thead { border-bottom: 2px solid var(--gray-300); }
    .table th {
        text-transform: uppercase;
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.5px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const createGradient = (ctx, color1, color2) => {
            const gradient = ctx.createLinearGradient(0, 0, 0, ctx.canvas.height);
            gradient.addColorStop(0, color1);
            gradient.addColorStop(1, color2);
            return gradient;
        };

        const tooltipOptions = {
            backgroundColor: '#111',
            titleFont: { size: 14, weight: 'bold' },
            bodyFont: { size: 12 },
            padding: 12,
            cornerRadius: 8,
            displayColors: false
        };

        // 1. Grafik Penjualan (Dinamis Harian/Bulanan)
        const ctxPenjualan = document.getElementById('penjualanChart').getContext('2d');
        const gradientPenjualan = createGradient(ctxPenjualan, 'rgba(107, 175, 84, 0.4)', 'rgba(107, 175, 84, 0.01)');
        new Chart(ctxPenjualan, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Jumlah Penjualan',
                    data: {!! json_encode($chartData) !!},
                    backgroundColor: gradientPenjualan,
                    borderColor: 'rgba(107, 175, 84, 1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(107, 175, 84, 1)',
                    pointBorderColor: '#fff',
                    pointHoverRadius: 6,
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        ...tooltipOptions,
                        callbacks: {
                            label: context => `Jumlah Transaksi: ${context.parsed.y}`
                        }
                    }
                }
            }
        });

        // 2. Produk Terlaris Chart (Doughnut)
        const labelsProduk = {!! json_encode($labelsProduk) !!};
        if (labelsProduk.length > 0) {
            const ctxProduk = document.getElementById('produkTerlarisChart').getContext('2d');
            new Chart(ctxProduk, {
                type: 'doughnut',
                data: {
                    labels: labelsProduk,
                    datasets: [{
                        label: 'Total Dibeli',
                        data: {!! json_encode($dataProduk) !!},
                        backgroundColor: [
                            'rgba(78, 115, 223, 0.9)', 'rgba(28, 200, 138, 0.9)',
                            'rgba(246, 194, 62, 0.9)', 'rgba(231, 74, 59, 0.9)',
                            'rgba(110, 110, 110, 0.9)',
                        ],
                        borderColor: '#fff',
                        borderWidth: 3,
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' }, tooltip: tooltipOptions }
                }
            });
        }

        // 3. Grafik Penghasilan Desa (Bar Chart)
        const ctxPenghasilan = document.getElementById('penghasilanDesaChart').getContext('2d');
        new Chart(ctxPenghasilan, {
            type: 'bar',
            data: {
                labels: {!! json_encode($labelsPenghasilanBulanan) !!},
                datasets: [{
                    label: 'Total Penghasilan',
                    data: {!! json_encode($dataPenghasilanBulanan) !!},
                    backgroundColor: 'rgba(246, 194, 62, 0.8)',
                    borderColor: 'rgba(246, 194, 62, 1)',
                    borderWidth: 2,
                    borderRadius: 5,
                    hoverBackgroundColor: 'rgba(246, 194, 62, 1)'
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => 'Rp ' + new Intl.NumberFormat('id-ID').format(value)
                        }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        ...tooltipOptions,
                        callbacks: {
                            label: context => `Penghasilan: Rp ${new Intl.NumberFormat('id-ID').format(context.parsed.y)}`
                        }
                    }
                }
            }
        });
    });
</script>
@endpush