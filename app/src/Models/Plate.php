<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class Plate extends Model
{

    use SoftDeletes;

    protected $primaryKey = 'email';
    protected $table = 'plates';
    protected $dates = ['deleted_at'];


}