<?php

namespace App\Http\Controllers;

use App\Models\Penduduk;
use App\Imports\PendudukImport;
use App\Exports\PendudukExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PendudukController extends Controller
{
    public function index()
    {
        $penduduk = Penduduk::orderBy('periode')->paginate(10);
        return view('penduduk.index', compact('penduduk'));
    }

    public function create()
    {
        return view('penduduk.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'periode' => 'required|string|size:7|unique:penduduk,periode',
            'kelahiran' => 'required|integer',
            'kematian' => 'required|integer',
            'migrasi_masuk' => 'required|integer',
            'migrasi_keluar' => 'required|integer',
            'jumlah_penduduk' => 'required|integer',
        ]);

        Penduduk::create($validated);
        return redirect()->route('penduduk.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit(Penduduk $penduduk)
    {
        return view('penduduk.edit', compact('penduduk'));
    }

    public function update(Request $request, Penduduk $penduduk)
    {
        $validated = $request->validate([
            'periode' => 'required|string|size:7|unique:penduduk,periode,' . $penduduk->id,
            'kelahiran' => 'required|integer',
            'kematian' => 'required|integer',
            'migrasi_masuk' => 'required|integer',
            'migrasi_keluar' => 'required|integer',
            'jumlah_penduduk' => 'required|integer',
        ]);

        $penduduk->update($validated);
        return redirect()->route('penduduk.index')->with('success', 'Data berhasil diupdate');
    }

    public function destroy(Penduduk $penduduk)
    {
        $penduduk->delete();
        return redirect()->route('penduduk.index')->with('success', 'Data berhasil dihapus');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new PendudukImport, $request->file('file'));
        return redirect()->back()->with('success', 'Data berhasil diimport');
    }

    public function export()
    {
        return Excel::download(new PendudukExport, 'data_penduduk.xlsx');
    }


    public function destroyAll()
    {
    Penduduk::truncate();
    return redirect()->route('penduduk.index')->with('success', 'Seluruh data penduduk berhasil dihapus.');
    }
}