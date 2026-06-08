<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    ];

    protected $casts = [
        'date' => 'date',
        'jenis_transaksi' => 'string',
        'jumlah' => 'integer',
    ];

    public function akun()
    {
        return $this->belongsTo(Akun::class, 'akun_id');
    }

    public function penginput()
    {
        return $this->belongsTo(User::class, 'penginput_id');
    }

    public function penanggungJawab()
    {
        return $this->belongsTo(PenanggungJawab::class, 'penanggung_jawab_id');
    }
}
