<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Akun extends Model
{
    use SoftDeletes;

    protected $table = 'akun';

    protected $fillable = [
        'riil_terakhir',
        'nama_akun',
        'kas',
        'keterangan',
        'jumlah',
    ];

    protected $casts = [
        'kas' => 'string',
        'jumlah' => 'decimal:2',
    ];

    public function riilHistory()
    {
        return $this->belongsTo(RiilHistory::class, 'riil_terakhir')->withTrashed();
    }

    public function riilHistories()
    {
        return $this->hasMany(RiilHistory::class, 'akun_id');
    }

    public function mutasiDebit()
    {
        return $this->hasMany(MutasiRekening::class, 'akun_debit_id');
    }

    public function mutasiKredit()
    {
        return $this->hasMany(MutasiRekening::class, 'akun_kredit_id');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'akun_id');
    }

    /**
     * @param  Builder<Akun>  $query
     * @param  array<string>|null  $kas
     */
    public function scopeFilter($query, ?string $search = null, ?array $kas = null)
    {
        if ($search) {
            $query->where('nama_akun', 'like', "%$search%");
        }

        if ($kas) {
            $query->whereIn('kas', $kas);
        }

        return $query;
    }

    /**
     * @param  Builder<Akun>  $query
     * @return Builder<Akun>
     */
    public function scopeFilterDropdown($query, ?string $search = null, ?string $kas = null)
    {
        if ($search) {
            $query->where('nama_akun', 'like', "%$search%");
        }

        if ($kas) {
            $query->where('kas', $kas);
        }

        return $query;
    }
}
