<?php

namespace App\Models;

use App\Traits\UuidModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class Plate extends Model
{
    use UuidModel;
    use SoftDeletes;


    protected $primaryKey = 'id';
    protected $table = 'plates';
    protected $dates = ['deleted_at'];

    public $incrementing = false;

    /**
     * Get the user that owns the plate.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}