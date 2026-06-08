<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    public function akunDebit()
    {
        return $this->belongsTo(Akun::class, 'akun_debit_id');
    }

    public function akunKredit()
    {
        return $this->belongsTo(Akun::class, 'akun_kredit_id');
    }
}
