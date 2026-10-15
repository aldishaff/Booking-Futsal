<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LapanganController extends Controller
{
    public function index()
    {
        $lapangans = Lapangan::latest()->paginate(5);
        return view('admin.lapangan.index', compact('lapangans'));
    }

    public function create()
    {
        return view('admin.lapangan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'            => 'required|string|max:100',
            'gambar'          => 'required|image|mimes:jpg,jpeg,png|max:4096',
            'jenis_lantai'    => 'required',
            'harga_per_jam'   => 'required|numeric',
            'ukuran'          => 'required|string|max:50',
        ]);

        $path = $request->file('gambar')->store('lapangan','public');

        Lapangan::create([
            'nama'           => $request->nama,
            'gambar'         => $path,
            'jenis_lantai'   => $request->jenis_lantai,
            'harga_per_jam'  => $request->harga_per_jam,
            'ukuran'         => $request->ukuran,
        ]);

        return redirect()
            ->route('admin.lapangan.index')
            ->with('success','Lapangan berhasil ditambahkan');
    }

    public function edit(Lapangan $lapangan)
    {
        return view('admin.lapangan.edit', compact('lapangan'));
    }

    public function update(Request $request, Lapangan $lapangan)
    {
        $request->validate([
            'nama'           => 'required|string|max:100',
            'jenis_lantai'   => 'required',
            'harga_per_jam'  => 'required|numeric',
            'ukuran'         => 'required|string|max:50',
            'gambar'         => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        if ($request->hasFile('gambar')) {
            if ($lapangan->gambar) {
                Storage::disk('public')->delete($lapangan->gambar);
            }

            $lapangan->gambar = $request->file('gambar')
                ->store('lapangan','public');
        }

        $lapangan->update([
            'nama'          => $request->nama,
            'jenis_lantai'  => $request->jenis_lantai,
            'harga_per_jam' => $request->harga_per_jam,
            'ukuran'        => $request->ukuran,
            'gambar'        => $lapangan->gambar,
        ]);

        return redirect()
            ->route('admin.lapangan.index')
            ->with('success','Data lapangan berhasil diperbarui');
    }

    public function destroy(Lapangan $lapangan)
    {
        if ($lapangan->gambar) {
            Storage::disk('public')->delete($lapangan->gambar);
        }

        $lapangan->delete();

        return redirect()
            ->route('admin.lapangan.index')
            ->with('success','Data lapangan berhasil dihapus');
    }


}
