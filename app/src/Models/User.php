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
    protected $dates = ['deleted_at'];

    public $incrementing = false;

    /**
     * Get the phone record associated with the user.
     */
    public function phone()
    {
        return $this->hasOne('App\Models\Phone');
    }


}