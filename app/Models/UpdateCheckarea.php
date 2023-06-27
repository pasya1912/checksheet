<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpdateCheckarea extends Model
{
    use HasFactory;
    protected $table = 'tm_checksheet';
    //updated_at false
    public $timestamps = true;

    public function checkarea(){
        return $this->belongsTo(Checkarea::class,'id_checksheet','id');
        
    }
}
