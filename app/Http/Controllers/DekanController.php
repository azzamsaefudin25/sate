<?php

namespace App\Http\Controllers;

use App\Models\PengalokasianRuang;
use App\Models\JadwalKuliah;
use Illuminate\Http\Request;

class DekanController extends Controller
{
    public function createPengajuanRuang()
    {
        // Ambil semua pengajuan dari tabel pengalokasianruang
        $pengajuans_ruang = PengalokasianRuang::all();

        // Kirim data ke view
        return view('dekan.approveruang', compact('pengajuans_ruang'));
    }

    public function createPengajuanJadwal()
    {
        // Ambil semua jadwal kuliah yang diajukan dari tabel JadwalKuliah
        $pengajuans = JadwalKuliah::all();

        // Kirim data ke view
        return view('dekan.approvejadwal', compact('pengajuans'));
    }

    // Menyetujui atau menolak pengalokasian ruang (diakses oleh dekan)
    public function updatePengajuanRuang(Request $request, $id)
    {
        $pengajuanruang = PengalokasianRuang::findOrFail($id);

        if ($request->input('action') === 'setuju') {
            // Update status menjadi 'disetujui'
            $pengajuanruang->status = 'disetujui';
            $pengajuanruang->save();

            return redirect()->route('dekan.approveruang')->with('message', 'Pengajuan dengan kode ruang ' . $pengajuanruang->kode_ruang . ' telah disetujui.');
        } elseif ($request->input('action') === 'tolak') {
            // Update status menjadi 'ditolak'
            $pengajuanruang->status = 'ditolak';
            $pengajuanruang->save();

            return redirect()->route('dekan.approveruang')->with('message', 'Pengajuan dengan kode ruang ' . $pengajuanruang->kode_ruang . ' telah ditolak dan dihapus.');
        }

        return redirect()->route('dekan.approveruang')->with('error', 'Tindakan tidak valid.');
    }


    // Menyetujui atau menolak jadwal kuliah (diakses oleh dekan)
    public function updatePengajuanJadwal(Request $request, $id)
    {
        $pengajuan = JadwalKuliah::findOrFail($id);

        if ($request->input('action') === 'setuju') {

            // Update status jadwal menjadi disetujui
            $pengajuan->status = 'disetujui';
            $pengajuan->save();
            
            return redirect()->route('dekan.approvejadwal')->with('message', 'Jadwal dengan kode MK ' . $pengajuan->kode_mk . ' telah disetujui.');
        } elseif ($request->input('action') === 'tolak') {
            
            // Hapus jadwal dari database
            $pengajuan->status = 'ditolak';
            $pengajuan->save();
            
            return redirect()->route('dekan.approvejadwal')->with('message', 'Jadwal dengan kode MK ' . $pengajuan->kode_mk . ' telah ditolak dan dihapus.');
        }

        return redirect()->route('dekan.approvejadwal')->with('error', 'Tindakan tidak valid.');
    }
}
