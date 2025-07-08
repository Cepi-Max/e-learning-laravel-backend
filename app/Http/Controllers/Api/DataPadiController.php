<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataPadi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DataPadiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil dari request, kalau tidak ada pakai bulan & tahun sekarang
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        // Ambil data sesuai bulan dan tahun
        $dataPadi = DataPadi::whereMonth('created_at', $bulan)
                            ->whereYear('created_at', $tahun)
                            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil ditemukan',
            'data' => $dataPadi
        ]);
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $datapadi = DataPadi::findOrFail($id);
        
        return response()->json([
            'status' => 'true',
            'message' => 'Data Detail Berhasil ditemukan',
            'data' => $datapadi
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(),[
            'nama' => 'required',
            'jumlah_padi' => 'required',
            'jenis_padi' => 'required',
            'foto_padi' => 'extensions:jpg,png',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => 'validasi error',
                'errors' => $validator->errors(),
            ], 422);
        }

        if ($request->hasFile('foto_padi') && $request->file('foto_padi')->isValid()) {
            $file = $request->file('foto_padi'); 
            $fileName = now()->format('Y-m-d_H-i-s') . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path   = 'images/petani/datapadi/fotopadi/'.$fileName;
            Storage::disk('public')->put($path, file_get_contents($file));
        } else {
             $fileName = 'default.png';
        }

        $id_author = auth()->user()->id;

        $datapadi = DataPadi::create([
            'nama' => $request->nama,
            'jumlah_padi' => $request->jumlah_padi,
            'jenis_padi' => $request->jenis_padi,
            // 'foto_padi' => $fileName,
            'user_id' => $id_author,
        ]);
        // dd($id_author);
              

        return response()->json([
            'status' => true,
            'message' => 'data berhasil ditambahkan',
            'data' => $datapadi
        ], 201);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $validator = Validator::make($request->all(),[
            'nama' => 'required',
            'jumlah_padi' => 'required',
            'jenis_padi' => 'required',
            'foto_padi' => 'nullable|mimes:jpg,png',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => 'validasi error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $datapadi = DataPadi::findOrFail($id);

        if ($request->hasFile('foto_padi') && $request->file('foto_padi')->isValid()) {
            $file = $request->file('foto_padi');

            $fileName = now()->format('Y-m-d_H-i-s') . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = 'images/petani/datapadi/fotopadi/'.$fileName;

            if ($datapadi->foto_padi && $datapadi->foto_padi !== 'default.png') {
                Storage::disk('public')->delete('images/petani/datapadi/fotopadi/'.$datapadi->foto_padi);
            }

            Storage::disk('public')->put($path, file_get_contents($file));

            $datapadi->foto_padi = $fileName;
        } else {
            $datapadi->foto_padi = $datapadi->foto_padi ?? 'default.png';
        }

        $id_author = auth()->user()->id;

        $datapadi->update([
            'nama' => $request->nama,
            'jumlah_padi' => $request->jumlah_padi,
            'jenis_padi' => $request->jenis_padi,
            // 'foto_padi' => $fileName,
            'user_id' => $id_author,
        ]);
        

        return response()->json([
            'status' => true,
            'message' => 'data berhasil diubah',
            'data' => $datapadi
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $datapadi = DataPadi::where('id', $id)->first();
        
        if (!empty($datapadi->foto_padi) && $datapadi->foto_padi !== 'default.png') {
            $filePath = 'images/petani/datapadi/fotopadi/' . $datapadi->foto_padi;
            Storage::disk('public')->delete($filePath);
        }

        $datapadi->delete();

        return response()->json([
            'status' => true,
            'message' => 'data berhasil dihapus'
        ], 200);
    }
}
