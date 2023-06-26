<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkarea extends Model
{
    use HasFactory;
    protected $table = 'tm_checkarea';
    //updated_at false
    public $timestamps = false;

    public function checksheet(){
        return $this->belongsTo(Checksheet::class,'id_checksheet','id');
    }
    public function checkdata(){
        return $this->hasMany(Checkdata::class,'id_checkarea','id');
    }
}
