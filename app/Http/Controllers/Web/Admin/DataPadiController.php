<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataPadi;
use Illuminate\Support\Facades\Storage;

class DataPadiController extends Controller
{
    
    public function index()
    {
        $dataPadi = DataPadi::latest()->paginate(10);
        return view('data_padi.index', compact('dataPadi'));
    }

    public function create()
    {
        return view('data_padi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'jumlah_padi' => 'required|numeric',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'foto_padi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto_padi')) {
            $validated['foto_padi'] = $request->file('foto_padi')->store('foto_padi', 'public');
        }

        DataPadi::create($validated);

        return redirect()->route('data_padi.index')->with('success', 'Data padi berhasil ditambahkan');
    }

    public function show($id)
    {
        $dataPadi = DataPadi::findOrFail($id);
        return view('data_padi.show', compact('dataPadi'));
    }

    public function edit($id)
    {
        $dataPadi = DataPadi::findOrFail($id);
        return view('data_padi.edit', compact('dataPadi'));
    }

    // Update data padi
    public function update(Request $request, $id)
    {
        $dataPadi = DataPadi::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string',
            'jumlah_padi' => 'required|numeric',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'foto_padi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle update foto jika ada
        if ($request->hasFile('foto_padi')) {
            // Hapus foto lama jika ada
            if ($dataPadi->foto_padi) {
                Storage::disk('public')->delete($dataPadi->foto_padi);
            }
            $validated['foto_padi'] = $request->file('foto_padi')->store('foto_padi', 'public');
        }

        $dataPadi->update($validated);

        return redirect()->route('data_padi.index')->with('success', 'Data padi berhasil diupdate');
    }

    // Hapus data padi
    public function destroy($id)
    {
        $dataPadi = DataPadi::findOrFail($id);
        if ($dataPadi->foto_padi) {
            Storage::disk('public')->delete($dataPadi->foto_padi);
        }
        $dataPadi->delete();

        return redirect()->route('data_padi.index')->with('success', 'Data padi berhasil dihapus');
    }
}
