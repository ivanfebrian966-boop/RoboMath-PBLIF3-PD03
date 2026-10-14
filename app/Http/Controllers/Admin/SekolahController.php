<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sekolah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SekolahController extends Controller
{
    public function index(): View
    {
        $sekolahList = Sekolah::withCount(['users', 'classRooms'])->latest()->get();

        return view('admin.sekolah.index', compact('sekolahList'));
    }

    public function create(): View
    {
        return view('admin.sekolah.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_sekolah' => ['required', 'string', 'max:255'],
            'nama_kota' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:A,N'],
        ]);

        Sekolah::create($validated);

        return redirect()->route('admin.sekolah.index')->with('success', 'Sekolah berhasil ditambahkan!');
    }

    public function edit(Sekolah $sekolah): View
    {
        return view('admin.sekolah.edit', compact('sekolah'));
    }

    public function update(Request $request, Sekolah $sekolah): RedirectResponse
    {
        $validated = $request->validate([
            'nama_sekolah' => ['required', 'string', 'max:255'],
            'nama_kota' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:A,N'],
        ]);

        $sekolah->update($validated);

        return redirect()->route('admin.sekolah.index')->with('success', 'Sekolah berhasil diperbarui!');
    }

    public function destroy(Sekolah $sekolah): RedirectResponse
    {
        $sekolah->delete();

        return redirect()->route('admin.sekolah.index')->with('success', 'Sekolah dihapus.');
    }
}
