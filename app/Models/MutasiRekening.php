<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $akun_debit_id
 * @property int $akun_kredit_id
 * @property Carbon|string $date
 * @property int $jumlah
 * @property string|null $keterangan
 */
class MutasiRekening extends Model
{
    use SoftDeletes;

    protected $table = 'mutasi_rekening';

    protected $fillable = [
        'akun_debit_id',
        'akun_kredit_id',
        'date',
        'jumlah',
        'keterangan',
    ];

    protected $casts = [
        'date' => 'date',
        'jumlah' => 'integer',
    ];

    public function akunDebit(): BelongsTo
    {
        return $this->belongsTo(Akun::class, 'akun_debit_id')->withTrashed();
    }

    public function akunKredit(): BelongsTo
    {
        return $this->belongsTo(Akun::class, 'akun_kredit_id')->withTrashed();
    }

    /**
     * @param  Builder<MutasiRekening>  $query
     * @param  array<string>  $kas
     * @return Builder<MutasiRekening>
     */
    public function scopeFilter($query, ?string $search = null, ?string $tanggal_mulai = null, ?string $tanggal_selesai = null, ?array $kas = null, ?int $akun = null)
    {
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('akunDebit', function ($q2) use ($search) {
                    $q2->where('nama_akun', 'like', "%$search%");
                })->orWhereHas('akunKredit', function ($q2) use ($search) {
                    $q2->where('nama_akun', 'like', "%$search%");
                })->orWhere('keterangan', 'like', "%$search%");
            });
        }

        if ($tanggal_mulai) {
            $query->whereDate('date', '>=', $tanggal_mulai);
        }

        if ($tanggal_selesai) {
            $query->whereDate('date', '<=', $tanggal_selesai);
        }

        if ($kas) {
            $query->where(function ($q) use ($kas) {
                $q->whereHas('akunDebit', function ($q2) use ($kas) {
                    $q2->whereIn('kas', $kas);
                })->orWhereHas('akunKredit', function ($q2) use ($kas) {
                    $q2->whereIn('kas', $kas);
                });
            });
        }

        if ($akun) {
            $query->where(function ($q) use ($akun) {
                $q->whereHas('akunDebit', function ($q2) use ($akun) {
                    $q2->where('id', $akun);
                })->orWhereHas('akunKredit', function ($q2) use ($akun) {
                    $q2->where('id', $akun);
                });
            });
        }

        return $query;
    }

    /**
     * @param  Builder<MutasiRekening>  $query
     * @return Builder<MutasiRekening>
     */
    public function scopeLaporanFilter($query, ?string $tanggal_mulai = null, ?string $tanggal_selesai = null, ?string $kas = null, ?int $akun = null)
    {
        if ($tanggal_mulai) {
            $query->whereDate('date', '>=', $tanggal_mulai);
        }

        if ($tanggal_selesai) {
            $query->whereDate('date', '<=', $tanggal_selesai);
        }

        if ($kas) {
            $query->where(function ($q) use ($kas) {
                $q->whereHas('akunDebit', function ($q2) use ($kas) {
                    $q2->where('kas', $kas);
                })->orWhereHas('akunKredit', function ($q2) use ($kas) {
                    $q2->where('kas', $kas);
                });
            });
        }

        if ($akun) {
            $query->where(function ($q) use ($akun) {
                $q->whereHas('akunDebit', function ($q2) use ($akun) {
                    $q2->where('id', $akun);
                })->orWhereHas('akunKredit', function ($q2) use ($akun) {
                    $q2->where('id', $akun);
                });
            });
        }

        return $query;
    }
}
