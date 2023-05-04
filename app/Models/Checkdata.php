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

}
