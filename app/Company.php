<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;
    protected $table = 'las_companies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_name', 'address', 'city', 'state', 'zip_postal', 'logo', 'created_at', 'updated_at', 'deleted_at'
    ];

    public function getAllData(){
        return self::with('companyUser')->get();
    }

    public function getUserCompanyData($company_id){
        return self::with('companyUser')->where('id', $company_id)->get();
    }

    public function companyUser(){
        return $this->hasOne('App\User','company_id','id');
    }
}
