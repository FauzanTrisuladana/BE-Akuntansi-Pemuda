<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property Akun|null $akun
 */
class RiilHistory extends Model
{
    use SoftDeletes;

    protected $table = 'riil_history';

    protected $fillable = [
        'akun_id',
        'date',
        'verified',
        'riil',
    ];

    protected $casts = [
        'date' => 'date',
        'verified' => 'boolean',
        'riil' => 'decimal:2',
    ];

    /**
     * @return BelongsTo<Akun, RiilHistory>
     */
    public function akun(): BelongsTo
    {
        return $this->belongsTo(Akun::class, 'akun_id')->withTrashed();
    }

    /**
     * @param  Builder<RiilHistory>  $query
     * @param  array<string>|null  $kas
     */
    public function scopeFilter($query, ?string $search = null, ?string $tanggal_mulai = null, ?string $tanggal_selesai = null, ?array $kas = null)
    {
        if ($search) {
            $query->whereHas('akun', function ($query) use ($search) {
                $query->where('nama_akun', 'like', "%$search%");
            });
        }

        if ($tanggal_mulai) {
            $query->where('date', '>=', $tanggal_mulai);
        }

        if ($tanggal_selesai) {
            $query->where('date', '<=', $tanggal_selesai);
        }

        if ($kas) {
            $query->whereHas('akun', function ($query) use ($kas) {
                $query->whereIn('kas', $kas);
            });
        }

        return $query;
    }
}
