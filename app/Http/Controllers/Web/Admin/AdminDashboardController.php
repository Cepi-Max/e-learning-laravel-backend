<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataPadi;
use App\Models\Ricesales\Order;
use App\Models\Ricesales\OrderItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama dengan data analitik.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // =======================================================================
        // 1. PERSIAPAN FILTER & VARIABEL DASAR (Tidak ada perubahan signifikan)
        // =======================================================================

        $bulanTerpilih = $request->input('bulan');
        $tahunTerpilih = $request->input('tahun', 2025); // Default tahun 2025

        $listBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $listTahun = range(Carbon::now()->year + 1, Carbon::now()->year - 4);

        if (!in_array($tahunTerpilih, $listTahun)) {
            array_unshift($listTahun, $tahunTerpilih);
        }

        // =======================================================================
        // 2. PENGUMPULAN DATA STATISTIK (STAT CARDS) (Tidak ada perubahan)
        // =======================================================================

        $users = User::where('role', 'petani')->count();

        $queryByDate = function ($query) use ($bulanTerpilih, $tahunTerpilih) {
            $query->whereYear('created_at', $tahunTerpilih);
            if ($bulanTerpilih) {
                $query->whereMonth('created_at', $bulanTerpilih);
            }
        };

        $totalPadi = DataPadi::where($queryByDate)->sum('jumlah_padi');

        $totalPenjualan = Order::where('is_paid', true)
            ->where($queryByDate)
            ->count();

        $penghasilanDesa = Order::where('is_paid', true)
            ->where($queryByDate)
            ->sum('total_price');

        // =======================================================================
        // 3. DATA UNTUK GRAFIK
        // =======================================================================

        // ================================================================================
        // --- PERBAIKAN UTAMA DI SINI ---
        // Logika untuk "Grafik Penjualan" sekarang dinamis berdasarkan filter
        // ================================================================================

        $chartTitle = '';
        $chartLabels = [];
        $chartData = [];

        if ($bulanTerpilih) {
            // ----- JIKA BULAN TERTENTU DIPILIH (TAMPILKAN DATA HARIAN) -----

            $namaBulan = $listBulan[$bulanTerpilih];
            $chartTitle = "Grafik Jumlah Penjualan Harian - $namaBulan $tahunTerpilih";
            $jumlahHari = Carbon::create($tahunTerpilih, $bulanTerpilih)->daysInMonth;

            // Query untuk mengambil jumlah penjualan per hari
            $penjualanHarian = Order::select(
                DB::raw('DAY(created_at) as hari'),
                DB::raw('COUNT(*) as total')
            )
                ->where('is_paid', true)
                ->whereYear('created_at', $tahunTerpilih)
                ->whereMonth('created_at', $bulanTerpilih)
                ->groupBy('hari')
                ->pluck('total', 'hari');

            // Isi label dengan tanggal (1, 2, 3...) dan data penjualannya
            for ($i = 1; $i <= $jumlahHari; $i++) {
                $chartLabels[] = $i;
                $chartData[] = $penjualanHarian[$i] ?? 0;
            }
        } else {
            // ----- JIKA "SEMUA BULAN" DIPILIH (TAMPILKAN DATA BULANAN) -----

            $chartTitle = "Grafik Jumlah Penjualan Bulanan - Tahun $tahunTerpilih";

            // Query untuk mengambil jumlah penjualan per bulan
            $penjualanBulanan = Order::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('COUNT(*) as total')
            )
                ->where('is_paid', true)
                ->whereYear('created_at', $tahunTerpilih)
                ->groupBy('bulan')
                ->pluck('total', 'bulan');

            // Isi label dengan nama bulan dan data penjualannya
            for ($i = 1; $i <= 12; $i++) {
                $chartLabels[] = Carbon::create(null, $i)->isoFormat('MMM'); // Format: Jan, Feb, Mar
                $chartData[] = $penjualanBulanan[$i] ?? 0;
            }
        }

        // --- AKHIR DARI PERBAIKAN UTAMA ---


        // b) Grafik Penghasilan Desa Bulanan (Tidak diubah, hanya perapian variabel)
        $queryPenghasilanBulanan = Order::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('SUM(total_price) as total')
            )
            ->where('is_paid', true)
            ->whereYear('created_at', $tahunTerpilih)
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->all();

        $labelsPenghasilan = [];
        $dataPenghasilan = [];
        for ($m = 1; $m <= 12; $m++) {
            $labelsPenghasilan[] = $listBulan[$m];
            $dataPenghasilan[] = $queryPenghasilanBulanan[$m] ?? 0;
        }

        // c) Grafik 5 Produk Terlaris (Tidak ada perubahan)
        $produkTerlaris = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_terjual'))
            ->with('product:id,name')
            ->whereHas('order', function ($q) use ($queryByDate) {
                $q->where('is_paid', true)->where($queryByDate);
            })
            ->groupBy('product_id')
            ->orderByDesc('total_terjual')
            ->take(5)
            ->get();

        $labelsProduk = $produkTerlaris->map(fn($item) => $item->product->name ?? 'Produk Dihapus');
        $dataProduk = $produkTerlaris->pluck('total_terjual');


        // =======================================================================
        // 4. DATA UNTUK TABEL (Tidak ada perubahan)
        // =======================================================================
        $dataPadi = DataPadi::latest()->paginate(10);

        // =======================================================================
        // 5. MENGIRIM SEMUA DATA KE VIEW
        // =======================================================================
        return view('admin.dashboard.index', [
            // Data Statistik
            'users' => $users,
            'totalPadi' => $totalPadi,
            'totalPenjualan' => $totalPenjualan,
            'penghasilanDesa' => $penghasilanDesa,

            // Data untuk Tabel
            'dataPadi' => $dataPadi,

            // --- PERUBAHAN DI SINI ---
            // Mengirim data grafik penjualan yang sudah dinamis
            'chartTitle' => $chartTitle,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,

            // Data Grafik Produk Terlaris
            'labelsProduk' => $labelsProduk,
            'dataProduk' => $dataProduk,

            // Data Grafik Penghasilan (variabel dirapikan agar tidak duplikat)
            'labelsPenghasilanBulanan' => $labelsPenghasilan,
            'dataPenghasilanBulanan' => $dataPenghasilan,

            // Data untuk Filter Dropdown
            'listBulan' => $listBulan,
            'listTahun' => $listTahun,
            'bulanTerpilih' => $bulanTerpilih ? (int) $bulanTerpilih : '',
            'tahunTerpilih' => (int) $tahunTerpilih,
        ]);
    }
}