<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Checkdata;

class ApprovalHistory extends Model
{
    use HasFactory;
    protected $table = 'approval_history';
    protected $fillable = [
        'id_checkdata',
        'approval',
        'user'
    ];
    public $timestamps = false;
    //relation to user with NPK
    public function owner()
    {
        return $this->belongsTo(User::class, 'user', 'npk');
    }
    public function checkdata()
    {
        return $this->belongsTo(Checkdata::class, 'id_checkdata', 'id');
    }
}
