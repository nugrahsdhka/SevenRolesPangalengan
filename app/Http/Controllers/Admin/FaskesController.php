<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faskes;
use Illuminate\Http\Request;

class FaskesController extends Controller
{
    public function index()
    {
        $faskes = Faskes::orderBy('nama_faskes')->paginate(10);

        return view('admin.faskes.index', compact('faskes'));
    }

    public function create()
    {
        return view('admin.faskes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_faskes'    => 'required|string|max:255',
            'jenis'          => 'required|string|max:100',
            'alamat'         => 'nullable|string|max:255',
            'desa_kelurahan' => 'nullable|string|max:100',
            'lat'            => 'required|numeric',
            'lng'            => 'required|numeric',
            'is_active'      => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        Faskes::create($data);

        return redirect()->route('admin.faskes.index')
            ->with('success', 'Faskes berhasil ditambahkan.');
    }

    public function edit(Faskes $faskes)
    {
        return view('admin.faskes.edit', compact('faskes'));
    }

    public function update(Request $request, Faskes $faskes)
    {
        $data = $request->validate([
            'nama_faskes'    => 'required|string|max:255',
            'jenis'          => 'required|string|max:100',
            'alamat'         => 'nullable|string|max:255',
            'desa_kelurahan' => 'nullable|string|max:100',
            'lat'            => 'required|numeric',
            'lng'            => 'required|numeric',
            'is_active'      => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        $faskes->update($data);

        return redirect()->route('admin.faskes.index')
            ->with('success', 'Faskes berhasil diperbarui.');
    }

    public function destroy(Faskes $faskes)
    {
        $faskes->delete();

        return redirect()->route('admin.faskes.index')
            ->with('success', 'Faskes berhasil dihapus.');
    }

    public function toggleActive(Faskes $faskes)
    {
        // Balikkan nilai is_active: 1 -> 0, 0 -> 1
        $faskes->is_active = ! $faskes->is_active;
        $faskes->save();

        return back()->with('success', 'Status faskes berhasil diperbarui.');
    }
}
