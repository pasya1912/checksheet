<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checksheet extends Model
{
    use HasFactory;
    protected $table = 'tm_checksheet';
    //updated_at false
    public $timestamps = false;

    public function checkarea(){
        return $this->hasMany(Checkarea::class,'id_checksheet','id');
    }
}
