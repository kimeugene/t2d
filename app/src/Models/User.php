<?php

namespace App\Models;

use App\Traits\UuidModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class User extends Model
{
    use UuidModel;
    use SoftDeletes;


    protected $primaryKey = 'id';
    protected $table = 'users';
    protected $hidden = ['id'];
    protected $dates = ['deleted_at'];


}