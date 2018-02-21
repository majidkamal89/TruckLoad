<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loads extends Model
{
    use SoftDeletes;
    protected $table = 'las_truck_lists_loads';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'truck_list_id', 'load_time', 'from_destination', 'to_destination', 'las_master_data_load_id', 'las_master_data_volume_id', 'quantity', 'notes', 'deleted_at'
    ];

    public function index($id)
    {
        $loads = self::with(['loadData','volumeData'])->where('truck_list_id', $id)->withTrashed()->get();
        return $loads;
    }

    public function getAllData($id=false)
    {
        if($id){
            $loads = self::with(['loadData','volumeData'])->whereIn('truck_list_id', $id)->get();
        } else {
            $loads = self::with(['loadData','volumeData'])->get();
        }
        return $loads;
    }

    public function loadData()
    {
        return $this->hasOne('App\Master','id','las_master_data_load_id')->withTrashed();
    }

    public function volumeData()
    {
        return $this->hasOne('App\Master','id','las_master_data_volume_id')->withTrashed();
    }
}
