<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Customer extends Model
{
    use SoftDeletes;
    protected $table = 'las_customers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_name', 'customer_address', 'user_id', 'signature', 'created_at', 'updated_at', 'deleted_at'
    ];
}
