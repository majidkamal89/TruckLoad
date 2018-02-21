<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trucklist extends Model
{
    use SoftDeletes;
    protected $table = 'las_truck_lists';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'customer_id', 'project_id', 'vehicle_id', 'signature', 'status', 'created_at', 'updated_at', 'deleted_at'
    ];

    public function index($user_id)
    {
        $result = self::with(['getCustomer','getProject','getVehicle'])->whereIn('user_id', $user_id)->get();
        return $result;
    }
    public function getDriverLoads($user_id,$trucklistid)
    {
        $result = self::with(['getCustomer','getProject','getVehicle','getLoads'])->where('user_id', $user_id)->where('id', $trucklistid)->get();
        return $result;
    }

    public function getCustomer()
    {
        return $this->hasOne('App\Customer','id','customer_id')->select(['id','customer_name'])->withTrashed();
    }
    public function getLoads()
    {
        return $this->hasMany('App\Loads','truck_list_id','id')->select( ['id',
            'truck_list_id', 'load_time', 'from_destination', 'to_destination', 'las_master_data_load_id', 'las_master_data_volume_id', 'quantity', 'notes', 'deleted_at'
        ]);
    }

    public function getProject()
    {
        return $this->hasOne('App\Project','id','project_id')->select(['id','project_name','project_code'])->withTrashed();
    }

    public function getVehicle()
    {
        return $this->hasOne('App\Master','id','vehicle_id')->select(['id','description','value'])->withTrashed();
    }
    // Fetch all loads of selected trucklist
    public function getTruckListLoad($user_id)
    {
        $result = self::with(['getTruckLoads','getProjectData'])
            ->where('user_id', $user_id)
            ->where('created_at', '>', date('Y-m-d 00:00:00'))
            ->where('created_at', '<=', date('Y-m-d 23:59:59'))
            ->get();
        return $result;
    }

    public function getTruckLoads()
    {
        return $this->hasMany('App\Loads','truck_list_id','id');
    }

    public function getProjectData()
    {
        return $this->hasOne('App\Project','id','project_id')->select(['id','project_name']);
    }

    public function getAllImages($truckListID, $user_id)
    {
        $result = self::with(['getImages'])
            ->where('id', $truckListID)
            ->first();
        return $result;
    }
    public function getImages()
    {
        return $this->hasMany('App\Trucklistimage','truck_list_id','id');
    }
}
