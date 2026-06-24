<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenanggungJawab extends Model
{
    use SoftDeletes;

    protected $table = 'penanggung_jawab';

    protected $fillable = [
        'nama',
    ];

    protected $casts = [];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'penanggung_jawab_id');
    }

    public function scopeFilter($query, ?string $search = null)
    {
        if ($search) {
            $query->where('nama', 'like', "%$search%");
        }

        return $query;
    }
}
