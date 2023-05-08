<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkdata extends Model
{
    use HasFactory;
    protected $table = 'tt_checkdata';
    //updated_at false
    public $timestamps = false;
    //set fillable
    protected $fillable=['id_checkarea','tanggal','shift','nama','barang','value','approval','mark','user','notesss'];

}
