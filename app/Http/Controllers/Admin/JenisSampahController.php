<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\JenisSampahRequest;
use App\Models\JenisSampah;
use Exception;

class JenisSampahController extends Controller
{
    public function index()
    {
        $data = JenisSampah::orderBy('id', 'desc')->paginate(10);
        return view('admin.jenis_sampah.index', compact('data'));
    }

    public function create()
    {
        return view('admin.jenis_sampah.create');
    }

    public function store(JenisSampahRequest $request)
    {
        try {
            JenisSampah::create($request->validated());
            return redirect()->route('admin.jenis-sampah.index')->with('success', 'Jenis sampah berhasil ditambahkan.');
        } catch (Exception $e) {
            return back()->withErrors('Gagal menyimpan data.')->withInput();
        }
    }

    public function edit($id)
    {
        $item = JenisSampah::findOrFail($id);
        return view('admin.jenis_sampah.edit', compact('item'));
    }

    public function update(JenisSampahRequest $request, $id)
    {
        try {
            $item = JenisSampah::findOrFail($id);
            $item->update($request->validated());

            return redirect()->route('admin.jenis-sampah.index')->with('success', 'Jenis sampah berhasil diperbarui.');
        } catch (Exception $e) {
            return back()->withErrors('Gagal memperbarui data.')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $item = JenisSampah::findOrFail($id);
            $item->delete();

            return redirect()->route('admin.jenis-sampah.index')->with('success', 'Jenis sampah berhasil dihapus.');
        } catch (Exception $e) {
            return back()->withErrors('Gagal menghapus data.');
        }
    }
    public function show($id)
        {
            $item = JenisSampah::findOrFail($id);
            return view('admin.jenis-sampah.show', compact('item'));
        }
}
