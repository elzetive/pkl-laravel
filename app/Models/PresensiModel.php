<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresensiModel extends Model
{
    protected $table    = 'tbl_presensi';
    protected $primaryKey = 'id_presensi';
    public $timestamps = false;
    protected $fillable = [
    'id_presensi',
    'id_pertemuan',
    'nim',
    'status_kehadiran',
    ];

    public function pertemuan()
    {
        return $this->belongsTo(PertemuanModel::class, 'id_pertemuan', 'id_pertemuan');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'nim', 'nim');
    }

}
