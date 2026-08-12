<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalLog extends Model
{
    protected $table = 'approval_logs';
    protected $primaryKey = 'id_approval';
    public $timestamps = false;

    protected $fillable = [
        'id_permohonan',
        'id_user_approver',
        'role_approver',
        'aksi',
        'catatan',
        'created_at',
    ];

    public function permohonanIzin()
    {
        return $this->belongsTo(PermohonanIzin::class, 'id_permohonan', 'id_permohonan');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'id_user_approver', 'id');
    }
}
