<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PertemuanModel extends Model
{
    use HasFactory;

    protected $table = 'tbl_pertemuan';

    protected $primaryKey = 'id_pertemuan';
    public $timestamps = false;

    protected $fillable = [
        'id_kelas',
        'pertemuan_ke',
        'judul_pertemuan',
        'tanggal',
        'status_pertemuan',
        'updated_at'
    ];

    protected $casts = [
        'tanggal'   => 'date',
    ];

    public function kelas()
    {
        return $this->belongsTo(KelasModel::class, 'id_kelas');
    }

}
