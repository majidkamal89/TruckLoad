<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Loads;
use App\Project;
use App\Trucklist;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::getUser()->user_type == 1){
            $allUsers = User::where('user_type', '<>', 1)->get();
            $managers = count($allUsers->where('user_type', 2));
            $drivers = count($allUsers->where('user_type', 3));
            $customers = Customer::get()->count();
            $projects = Project::get()->count();
            $trucklist = Trucklist::get()->count();
            $loads = Loads::get()->count();
            return View::make('admin.index', compact('managers','drivers','customers','projects','trucklist','loads'));
        } elseif(Auth::getUser()->user_type == 2){
            $drivers = User::where('company_id', Auth::getUser()->company_id)->where('user_type', 3)->get()->count();
            $customers = Customer::where('user_id', Auth::getUser()->id)->get()->count();
            $projects = Project::where('user_id', Auth::getUser()->id)->get()->count();
            $this->trucklist = new TrucklistController();
            $total_trucklist = $this->trucklist->getAllTruckList(true);
            $trucklist = $total_trucklist->count();
            $loads = 0;
            if(count($total_trucklist->pluck('id')->toArray()) > 0){
                $loads = Loads::whereIn('truck_list_id', array($total_trucklist->pluck('id')->toArray()))->get()->count();
            }
            return View::make('admin.manager_index', compact('drivers','customers','projects','trucklist','loads'));
        } elseif(Auth::getUser()->user_type == 3){
            return View::make('admin.driver_index');
        }
        return Redirect::to('/dashboard');
    }
}
