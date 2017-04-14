<?php

namespace App\Models;

use App\Traits\UuidModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class Phone extends Model
{
    use UuidModel;
    use SoftDeletes;


    protected $primaryKey = 'id';
    protected $table = 'phones';
    protected $dates = ['deleted_at'];

    public $incrementing = false;


}