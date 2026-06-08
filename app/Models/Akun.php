<?php

namespace App\Models;

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
    ];

    protected $casts = [
        'kas' => 'string',
    ];

    public function riilHistory()
    {
        return $this->belongsTo(RiilHistory::class, 'riil_terakhir');
    }

    public function riilHistories()
    {
        return $this->hasMany(RiilHistory::class, 'akun_id');
    }
}
