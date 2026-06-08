<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'riil' => 'decimal:15,2',
    ];

    public function akun()
    {
        return $this->belongsTo(Akun::class, 'akun_id');
    }
}
