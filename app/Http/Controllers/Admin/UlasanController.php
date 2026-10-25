<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ulasan;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UlasanController extends Controller
{
    public function index(Request $request)
    {
        // Filter status ulasan
        $status = $request->query('status', 'pending');

        $statusValid = ['pending', 'approved', 'rejected', 'semua'];

        if (!in_array($status, $statusValid, true)) {
            $status = 'pending';
        }

        // Ambil ulasan beserta data pengunjung, UMKM, dan produk
        $query = Ulasan::with([
            'user:id,name,email',
            'umkm:id,nama_umkm',
            'produk:id,nama_produk',
            'reviewer:id,name',
        ]);

        // Filter berdasarkan status
        if ($status !== 'semua') {
            $query->where('status', $status);
        }

        // Tampilkan ulasan terbaru terlebih dahulu
        $ulasan = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Jumlah ulasan berdasarkan status
        $jumlahPending = Ulasan::where('status', 'pending')->count();
        $jumlahApproved = Ulasan::where('status', 'approved')->count();
        $jumlahRejected = Ulasan::where('status', 'rejected')->count();

        return view('admin.ulasan.index', compact(
            'ulasan',
            'status',
            'jumlahPending',
            'jumlahApproved',
            'jumlahRejected'
        ));
    }

    // Menyetujui ulasan pengunjung
    public function approve(Ulasan $ulasan)
    {
        DB::transaction(function () use ($ulasan) {
            $item = Ulasan::whereKey($ulasan->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($item->status !== 'pending') {
                throw ValidationException::withMessages([
                    'ulasan' => 'Hanya ulasan yang menunggu persetujuan yang dapat disetujui.',
                ]);
            }

            // Simpan data sebelum perubahan
            $dataSebelum = $item->toArray();

            $item->update([
                'status' => 'approved',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'alasan_penolakan' => null,
            ]);

            // Catat persetujuan dalam Log Aktivitas
            ActivityLogger::log(
                'ulasan',
                'approve',
                $item,
                $dataSebelum,
                $item->fresh()->toArray(),
                'Menyetujui ulasan pengunjung ID: ' . $item->id
            );
        });

        return redirect()
            ->route('admin.ulasan.index')
            ->with('success', 'Ulasan berhasil disetujui.');
    }

    // Menolak ulasan pengunjung
    public function reject(Request $request, Ulasan $ulasan)
    {
        DB::transaction(function () use ($ulasan) {
            $item = Ulasan::whereKey($ulasan->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($item->status !== 'pending') {
                throw ValidationException::withMessages([
                    'ulasan' => 'Hanya ulasan yang menunggu persetujuan yang dapat ditolak.',
                ]);
            }

            // Simpan data sebelum perubahan
            $dataSebelum = $item->toArray();

            $item->update([
                'status' => 'rejected',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'alasan_penolakan' => null,
            ]);

            // Catat penolakan dalam Log Aktivitas
            ActivityLogger::log(
                'ulasan',
                'reject',
                $item,
                $dataSebelum,
                $item->fresh()->toArray(),
                'Menolak ulasan pengunjung ID: ' . $item->id
            );
        });

        return redirect()
            ->route('admin.ulasan.index')
            ->with('success', 'Ulasan berhasil ditolak.');
    }

    // Menghapus ulasan yang sudah disetujui atau ditolak
    public function destroy(Ulasan $ulasan)
    {
        DB::transaction(function () use ($ulasan) {
            $item = Ulasan::whereKey($ulasan->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Hanya ulasan yang sudah dimoderasi boleh dihapus
            if (!in_array($item->status, ['approved', 'rejected'], true)) {
                throw ValidationException::withMessages([
                    'ulasan' => 'Hanya ulasan yang sudah disetujui atau ditolak yang dapat dihapus.',
                ]);
            }

            // Simpan data ulasan sebelum dihapus
            $dataSebelum = $item->toArray();
            $statusSebelum = $item->status;

            // Identifikasi ulasan sebelum data dihapus
            $namaPengunjung = $item->user?->name ?? 'Pengunjung tidak tersedia';

            if ($item->produk_id) {
                $namaProduk = $item->produk?->nama_produk ?? 'Produk tidak tersedia';
                $namaUmkm = $item->produk?->umkm?->nama_umkm ?? 'UMKM tidak tersedia';

                $objekUlasan = 'produk "' . $namaProduk
                    . '" dari UMKM "' . $namaUmkm . '"';
            } else {
                $namaUmkm = $item->umkm?->nama_umkm ?? 'UMKM tidak tersedia';

                $objekUlasan = 'UMKM "' . $namaUmkm . '"';
            }

            $labelStatus = $statusSebelum === 'approved'
                ? 'Disetujui'
                : 'Ditolak';


            // Simpan identitas objek ulasan ke riwayat aktivitas
            $dataSebelum['jenis_ulasan'] = $item->produk_id
                ? 'Produk'
                : 'UMKM';

            $dataSebelum['nama_pengunjung'] = $namaPengunjung;

            $dataSebelum['nama_umkm'] = $namaUmkm;

            if ($item->produk_id) {
                $dataSebelum['nama_produk'] = $namaProduk;
            }

            // Hapus ulasan dari database
            $item->delete();

            // Catat penghapusan dalam Riwayat Aktivitas
            ActivityLogger::log(
                'ulasan',
                'delete',
                $item,
                $dataSebelum,
                null,
                'Menghapus ulasan ' . $objekUlasan
                    . ' oleh ' . $namaPengunjung
                    . ' (ID ulasan: ' . $item->id
                    . ', status sebelumnya: ' . $labelStatus . ')'
            );
        });

        return redirect()
            ->route('admin.ulasan.index', [
                'status' => in_array(request('status'), ['approved', 'rejected', 'semua'], true)
                    ? request('status')
                    : 'pending',
            ])
            ->with('success', 'Ulasan berhasil dihapus dan aktivitas telah dicatat.');
    }

}
