<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trucklistimage extends Model
{
    use SoftDeletes;
    protected $table = 'las_truck_lists_images';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'truck_list_id', 'image_name', 'image_path', 'deleted_at'
    ];
}
