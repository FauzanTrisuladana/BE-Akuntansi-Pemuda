<?php

namespace App\Http\Controllers;

use App\Http\Requests\MutasiRekening\IndexMutasiRekeningRequest;
use App\Http\Requests\MutasiRekening\StoreMutasiRekeningRequest;
use App\Http\Requests\MutasiRekening\UpdateMutasiRekeningRequest;
use App\Http\Resources\ApiResource;
use App\Http\Resources\ApiResourceCollection;
use App\Http\Resources\MutasiRekeningResource;
use App\Models\Akun;
use App\Models\MutasiRekening;
use App\Models\RiilHistory;
use Illuminate\Support\Facades\DB;

class MutasiRekeningController extends Controller
{
    /**
     * Display a listing of the resource.
     * Get /api/mutasi-rekening
     */
    public function index(IndexMutasiRekeningRequest $request): ApiResourceCollection
    {
        $validated = $request->validated();

        $mutasi = MutasiRekening::with(['akunDebit', 'akunKredit'])
            ->filter(
                search: $validated['search'] ?? null,
                tanggal_mulai: $validated['tanggal_mulai'] ?? null,
                tanggal_selesai: $validated['tanggal_selesai'] ?? null,
                kas: $validated['kas'] ?? null,
                akun: $validated['akun'] ?? null,
            )
            ->orderBy('date', 'desc')
            ->paginate($validated['per_page']);

        return MutasiRekeningResource::collection($mutasi)
            ->message('Data mutasi rekening berhasil diambil');
    }

    /**
     * Store a newly created resource in storage.
     * Post /api/mutasi-rekening
     */
    public function store(StoreMutasiRekeningRequest $request): MutasiRekeningResource
    {
        $validated = $request->validated();

        $mutasi = DB::transaction(function () use ($validated) {
            $mutasi = MutasiRekening::create($validated);

            // Update riil history akun debit - ambil dari riil history terakhir sebelum tanggal
            $riilDebit = RiilHistory::where('akun_id', $validated['akun_debit_id'])
                ->where('date', '<=', $validated['date'])
                ->orderBy('date', 'desc')
                ->orderBy('id', 'desc')
                ->first();
            $jumlahDebit = $riilDebit ? $riilDebit->riil : 0;
            $jumlahDebit += $validated['jumlah'];
            RiilHistory::updateOrCreate([
                'akun_id' => $validated['akun_debit_id'],
                'date' => $validated['date'],
            ], [
                'riil' => $jumlahDebit,
                'verified' => false,
            ]);

            // Update riil history akun kredit - ambil dari riil history terakhir sebelum tanggal
            $riilKredit = RiilHistory::where('akun_id', $validated['akun_kredit_id'])
                ->where('date', '<=', $validated['date'])
                ->orderBy('date', 'desc')
                ->orderBy('id', 'desc')
                ->first();
            $jumlahKredit = $riilKredit ? $riilKredit->riil : 0;
            $jumlahKredit -= $validated['jumlah'];
            RiilHistory::updateOrCreate([
                'akun_id' => $validated['akun_kredit_id'],
                'date' => $validated['date'],
            ], [
                'riil' => $jumlahKredit,
                'verified' => false,
            ]);

            // Update semua riil history setelah tanggal ini dengan nilai baru
            $this->updateRiilSetelahnya($validated['akun_debit_id'], $validated['date'], $validated['jumlah'], 'debit');
            $this->updateRiilSetelahnya($validated['akun_kredit_id'], $validated['date'], $validated['jumlah'], 'kredit');

            return $mutasi;
        });

        return (new MutasiRekeningResource($mutasi->load(['akunDebit', 'akunKredit'])))
            ->message('Mutasi rekening berhasil dibuat');
    }

    /**
     * Update the specified resource in storage.
     * Put /api/mutasi-rekening/{id}
     */
    public function update(UpdateMutasiRekeningRequest $request, string $id): MutasiRekeningResource
    {
        $validated = $request->validated();

        $mutasi = MutasiRekening::findOrFail($id);

        // Simpan nilai lama untuk perhitungan koreksi
        $jumlahLama = $mutasi->jumlah;
        $akunDebitId = $mutasi->akun_debit_id;
        $akunKreditId = $mutasi->akun_kredit_id;
        $date = $mutasi->date;

        $mutasi = DB::transaction(function () use ($validated, $mutasi, $jumlahLama, $akunDebitId, $akunKreditId, $date) {
            $mutasi->update($validated);

            // Hitung selisih jumlah
            $selisih = $validated['jumlah'] - $jumlahLama;

            // Update riil history akun debit - ambil dari riil history terakhir sebelum tanggal
            $riilDebit = RiilHistory::where('akun_id', $akunDebitId)
                ->where('date', '<=', $date)
                ->orderBy('date', 'desc')
                ->orderBy('id', 'desc')
                ->first();
            $jumlahDebit = $riilDebit ? $riilDebit->riil : 0;
            $jumlahDebit += $selisih;
            RiilHistory::updateOrCreate([
                'akun_id' => $akunDebitId,
                'date' => $date,
            ], [
                'riil' => $jumlahDebit,
                'verified' => false,
            ]);

            // Update riil history akun kredit - ambil dari riil history terakhir sebelum tanggal
            $riilKredit = RiilHistory::where('akun_id', $akunKreditId)
                ->where('date', '<=', $date)
                ->orderBy('date', 'desc')
                ->orderBy('id', 'desc')
                ->first();
            $jumlahKredit = $riilKredit ? $riilKredit->riil : 0;
            $jumlahKredit -= $selisih;
            RiilHistory::updateOrCreate([
                'akun_id' => $akunKreditId,
                'date' => $date,
            ], [
                'riil' => $jumlahKredit,
                'verified' => false,
            ]);

            // Update semua riil history setelah tanggal ini dengan selisih
            $this->updateRiilSetelahnya($akunDebitId, $date, $selisih, 'debit');
            $this->updateRiilSetelahnya($akunKreditId, $date, $selisih, 'kredit');

            return $mutasi;
        });

        return (new MutasiRekeningResource($mutasi->load(['akunDebit', 'akunKredit'])))
            ->message('Mutasi rekening berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     * Delete /api/mutasi-rekening/{id}
     */
    public function destroy(string $id): ApiResource
    {
        $mutasi = MutasiRekening::findOrFail($id);

        DB::transaction(function () use ($mutasi) {
            // Update riil history akun debit - ambil dari riil history terakhir sebelum tanggal
            $riilDebit = RiilHistory::where('akun_id', $mutasi->akun_debit_id)
                ->where('date', '<=', $mutasi->date)
                ->orderBy('date', 'desc')
                ->orderBy('id', 'desc')
                ->first();
            $jumlahDebit = $riilDebit ? $riilDebit->riil : 0;
            $jumlahDebit -= $mutasi->jumlah;
            RiilHistory::updateOrCreate([
                'akun_id' => $mutasi->akun_debit_id,
                'date' => $mutasi->date,
            ], [
                'riil' => $jumlahDebit,
                'verified' => false,
            ]);

            // Update riil history akun kredit - ambil dari riil history terakhir sebelum tanggal
            $riilKredit = RiilHistory::where('akun_id', $mutasi->akun_kredit_id)
                ->where('date', '<=', $mutasi->date)
                ->orderBy('date', 'desc')
                ->orderBy('id', 'desc')
                ->first();
            $jumlahKredit = $riilKredit ? $riilKredit->riil : 0;
            $jumlahKredit += $mutasi->jumlah;
            RiilHistory::updateOrCreate([
                'akun_id' => $mutasi->akun_kredit_id,
                'date' => $mutasi->date,
            ], [
                'riil' => $jumlahKredit,
                'verified' => false,
            ]);

            // Update semua riil history setelah tanggal ini dengan nilai baru
            $this->updateRiilSetelahnya($mutasi->akun_debit_id, $mutasi->date, -$mutasi->jumlah, 'debit');
            $this->updateRiilSetelahnya($mutasi->akun_kredit_id, $mutasi->date, -$mutasi->jumlah, 'kredit');

            $mutasi->delete();

        });

        return (new ApiResource(null))
            ->message('Mutasi rekening berhasil dihapus');
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
            if ($tipe === 'debit') {
                $history->riil += $selisih;
            } else {
                $history->riil -= $selisih;
            }
            $history->verified = false; // Set verified menjadi false karena ada perubahan
            $history->save();
        }
    }
}
