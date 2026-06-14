<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $akun_id
 * @property int $penginput_id
 * @property int|null $penanggung_jawab_id
 * @property string $deskripsi
 * @property Carbon|string $date
 * @property string $jenis_transaksi
 * @property int $jumlah
 * @property string|null $bukti
 */
class Transaksi extends Model
{
    use SoftDeletes;

    protected $table = 'transaksi';

    protected $fillable = [
        'akun_id',
        'penginput_id',
        'penanggung_jawab_id',
        'deskripsi',
        'date',
        'jenis_transaksi',
        'jumlah',
        'bukti',
        'bukti_public_id',
    ];

    protected $casts = [
        'date' => 'date',
        'jenis_transaksi' => 'string',
        'jumlah' => 'integer',
    ];

    public function akun()
    {
        return $this->belongsTo(Akun::class, 'akun_id')->withTrashed();
    }

    public function penginput()
    {
        return $this->belongsTo(User::class, 'penginput_id')->withTrashed();
    }

    public function penanggungJawab()
    {
        return $this->belongsTo(PenanggungJawab::class, 'penanggung_jawab_id')->withTrashed();
    }

    /**
     * @param  Builder<Transaksi>  $query
     * @param  array<string>|null  $jenis_transaksi
     * @param  array<string>|null  $kas
     * @return Builder<Transaksi>
     */
    public function scopeFilter($query, ?string $search = null, ?string $tanggal_mulai = null, ?string $tanggal_selesai = null, ?array $jenis_transaksi = null, ?array $kas = null, ?int $akun = null)
    {
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('deskripsi', 'like', "%$search%")
                    ->orWhereHas('akun', function ($q2) use ($search) {
                        $q2->where('nama_akun', 'like', "%$search%");
                    })
                    ->orWhereHas('penginput', function ($q3) use ($search) {
                        $q3->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('penanggungJawab', function ($q4) use ($search) {
                        $q4->where('nama', 'like', "%$search%");
                    });
            });
        }

        if ($tanggal_mulai) {
            $query->where('date', '>=', $tanggal_mulai);
        }

        if ($tanggal_selesai) {
            $query->where('date', '<=', $tanggal_selesai);
        }

        if ($jenis_transaksi) {
            $query->whereIn('jenis_transaksi', $jenis_transaksi);
        }

        if ($kas) {
            $query->whereHas('akun', function ($q) use ($kas) {
                $q->whereIn('kas', $kas);
            });
        }

        if ($akun) {
            $query->where('akun_id', $akun);
        }

        return $query;
    }
}
