<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transaksi\IndexTransaksiRequest;
use App\Http\Requests\Transaksi\StoreTransaksiRequest;
use App\Http\Requests\Transaksi\UpdateTransaksiRequest;
use App\Http\Resources\ApiResource;
use App\Http\Resources\ApiResourceCollection;
use App\Http\Resources\TransaksiResource;
use App\Models\RiilHistory;
use App\Models\Transaksi;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     * Get api/transaksi
     */
    public function index(IndexTransaksiRequest $request): ApiResourceCollection
    {
        $validated = $request->validated();

        $transaksi = Transaksi::with(['akun', 'penanggungJawab', 'penginput'])->filter(
            search: $validated['search'] ?? null,
            tanggal_mulai: $validated['tanggal_mulai'] ?? null,
            tanggal_selesai: $validated['tanggal_selesai'] ?? null,
            jenis_transaksi: $validated['jenis_transaksi'] ?? null,
            kas: $validated['kas'] ?? null,
            akun: $validated['akun'] ?? null,
        )
            ->orderBy('date', 'desc')
            ->paginate($validated['per_page']);

        $summary = Transaksi::filter(
            search: $validated['search'] ?? null,
            tanggal_mulai: $validated['tanggal_mulai'] ?? null,
            tanggal_selesai: $validated['tanggal_selesai'] ?? null,
            jenis_transaksi: $validated['jenis_transaksi'] ?? null,
            kas: $validated['kas'] ?? null,
            akun: $validated['akun'] ?? null,
        )
            ->selectRaw('
                SUM(CASE WHEN jenis_transaksi = "pemasukan" THEN jumlah ELSE 0 END) as total_pemasukan,
                SUM(CASE WHEN jenis_transaksi = "pengeluaran" THEN jumlah ELSE 0 END) as total_pengeluaran
            ')
            ->first();

        return TransaksiResource::collection($transaksi)
            ->message('Data transaksi berhasil diambil')
            ->additional(['summary' => $summary]);
    }

    /**
     * Store a newly created resource in storage.
     * Post api/transaksi
     */
    public function store(StoreTransaksiRequest $request): TransaksiResource
    {
        $validated = $request->validated();

        if ($request->hasFile('bukti')) {
            $upload = Cloudinary::uploadApi()->upload($request->file('bukti')->getRealPath(), [
                'folder' => 'bukti_transaksi',
                'resource_type' => 'auto',
            ]);
            $validated['bukti'] = $upload['secure_url'];
            $validated['bukti_public_id'] = $upload['public_id'];
        }

        $transaksi = DB::transaction(function () use ($validated) {
            $riil = RiilHistory::where('akun_id', $validated['akun_id'])
                ->where('date', '<=', $validated['date'])
                ->orderBy('date', 'desc')
                ->orderBy('id', 'desc')
                ->first();
            $jumlah = $riil ? $riil->riil : 0;
            $jumlah = $validated['jenis_transaksi'] === 'pemasukan' ? $jumlah + $validated['jumlah'] : $jumlah - $validated['jumlah'];

            $transaksi = Transaksi::create($validated);

            RiilHistory::updateOrCreate([
                'akun_id' => $validated['akun_id'],
                'date' => $validated['date'],
            ], [
                'riil' => $jumlah,
            ]);

            $this->updateRiilSetelahnya(
                akunId: $validated['akun_id'],
                date: $validated['date'],
                selisih: $validated['jumlah'],
                tipe: $validated['jenis_transaksi'],
            );

            return $transaksi;
        });

        return (new TransaksiResource($transaksi->load(['akun', 'penanggungJawab', 'penginput'])))
            ->message('Transaksi berhasil dibuat');
    }

    /**
     * Update the specified resource in storage.
     * Put api/transaksi/{id}
     */
    public function update(UpdateTransaksiRequest $request, string $id): TransaksiResource
    {
        $validated = $request->validated();

        $transaksi = Transaksi::findOrFail($id);

        if ($request->hasFile('bukti')) {
            if ($transaksi->bukti_public_id) {
                Cloudinary::uploadApi()->destroy($transaksi->bukti_public_id);
            }
            $upload = Cloudinary::uploadApi()->upload($request->file('bukti')->getRealPath(), [
                'folder' => 'bukti_transaksi',
                'resource_type' => 'auto',
            ]);
            $validated['bukti'] = $upload['secure_url'];
            $validated['bukti_public_id'] = $upload['public_id'];
        }

        $transaksi = DB::transaction(function () use ($transaksi, $validated) {
            $riil = RiilHistory::where('akun_id', $transaksi->akun_id)
                ->where('date', '<=', $transaksi->date)
                ->orderBy('date', 'desc')
                ->orderBy('id', 'desc')
                ->first();

            $selisih = $validated['jumlah'] - $transaksi->jumlah;
            if ($transaksi->jenis_transaksi === 'pemasukan') {
                $jumlah = $riil ? $riil->riil : 0;
                $jumlah += $selisih;
            } else {
                $jumlah = $riil ? $riil->riil : 0;
                $jumlah -= $selisih;
            }

            $transaksi->update($validated);

            RiilHistory::updateOrCreate([
                'akun_id' => $transaksi->akun_id,
                'date' => $transaksi->date,
            ], [
                'riil' => $jumlah,
            ]);

            $this->updateRiilSetelahnya(
                akunId: $transaksi->akun_id,
                date: $transaksi->date,
                selisih: $selisih,
                tipe: $transaksi->jenis_transaksi,
            );

            return $transaksi;
        });

        return (new TransaksiResource($transaksi->load(['akun', 'penanggungJawab', 'penginput'])))
            ->message('Transaksi berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     * Delete api/transaksi/{id}
     */
    public function destroy(string $id): ApiResource
    {
        $transaksi = Transaksi::findOrFail($id);

        DB::transaction(function () use ($transaksi) {
            $riil = RiilHistory::where('akun_id', $transaksi->akun_id)
                ->where('date', '<=', $transaksi->date)
                ->orderBy('date', 'desc')
                ->orderBy('id', 'desc')
                ->first();
            $jumlah = $riil ? $riil->riil : 0;
            $jumlah = $transaksi->jenis_transaksi === 'pemasukan' ? $jumlah - $transaksi->jumlah : $jumlah + $transaksi->jumlah;
            RiilHistory::updateOrCreate([
                'akun_id' => $transaksi->akun_id,
                'date' => $transaksi->date,
            ], [
                'riil' => $jumlah,
            ]);

            $this->updateRiilSetelahnya(
                akunId: $transaksi->akun_id,
                date: $transaksi->date,
                selisih: $transaksi->jumlah,
                tipe: $transaksi->jenis_transaksi === 'pemasukan' ? 'pengeluaran' : 'pemasukan',
            );

            $transaksi->delete();
        });

        return (new ApiResource(null))
            ->message('Transaksi berhasil dihapus');
    }

    /**
     * Update riil history setelah tanggal tertentu dengan selisih.
     */
    private function updateRiilSetelahnya(int $akunId, string $date, int $selisih, string $tipe): void
    {
        $riilSetelahnya = RiilHistory::where('akun_id', $akunId)
            ->where('date', '>', $date)
            ->orderBy('date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        foreach ($riilSetelahnya as $history) {
            if ($tipe === 'pemasukan') {
                $history->riil += $selisih;
            } else {
                $history->riil -= $selisih;
            }
            $history->save();
        }
    }
}
