<?php

namespace App\Models;

use App\Traits\UuidModel;
use Illuminate\Database\Eloquent\Model;


class User extends Model
{
    use UuidModel;

    protected $primaryKey = 'id';
    protected $table = 'users';
    protected $hidden = ['id'];

}