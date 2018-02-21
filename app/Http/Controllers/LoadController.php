<?php

namespace App\Http\Controllers;

use App\Company;
use App\Loads;
use App\Trucklist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Master;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use League\Flysystem\Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use Illuminate\Support\Facades\Validator;
use PDF;

class LoadController extends Controller
{
    /**
     * Fetch all master data type 2.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllLoad($type)
    {
        $user_id = $this->getUserData();
        return Response()->json(Master::where('type', $type)->where('user_id', $user_id)->get());
    }
    // Method to save loads data
    public function store(Request $request, $truckListId)
    {
        $rules = array(
            'load_time' => 'required |date|after:today',
            'from_destination' => 'required | between:3,15',
            'to_destination' => 'required | between:3,15',
            'las_master_data_load_id' => 'required',
            'las_master_data_volume_id' => 'required',
            'quantity' => 'required',
            'notes' => 'required | between:10,100',
        );
        $credentials = $request->all();
        $validation = Validator($credentials, $rules);
        $request->request->add(['created_at' => date('Y-m-d H:i:s')]);
        $request->request->add(['updated_at' => date('Y-m-d H:i:s')]);
        $request->request->add(['truck_list_id' => $truckListId]);
        if ($validation->fails()){
            return response()->json($validation->messages(), 500);
        }
        try{
            Loads::create($request->all());
            return Response()->json(['success' => 'Sucess']);
        } catch (Exception $e){
            return ($e->getMessage());
            return Response()->json(['error' => 'Sorry something went worng. Please try again.']);
        }
        return Response()->json(['success' => $truckListId]);
    }

    /**
     * Show the Loads view.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewLoads($truckListId)
    {
        $truckListData = DB::table('las_truck_lists')
            ->join('las_projects', 'las_truck_lists.project_id', '=', 'las_projects.id')
            ->where('las_truck_lists.id', $truckListId)->first(['project_name']);
        $project_name = $truckListData->project_name;
        $truck_list_id = $truckListId;
        return View::make('admin.loads.index', compact('truck_list_id', 'project_name'));
    }

    /**
     * Update the specified  in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($trucklistid, $loadid, Request $request)
    {
        $rules = array(
            'load_time' => 'required |date|after:today',
            'from_destination' => 'required | between:3,15',
            'to_destination' => 'required | between:3,15',
            'las_master_data_load_id' => 'required',
            'las_master_data_volume_id' => 'required',
            'quantity' => 'required',
            'notes' => 'required | between:10,100',
        );

        $credentials = $request->all();

        $validation = Validator($credentials, $rules);
        if ($validation->fails()){
            return response()->json($validation->messages(), 500);
        }
        try {
            Loads::where('id', $loadid)->where('truck_list_id', $trucklistid)->update($credentials);
            $message = 'Record Updated successfully!';
        } catch(\Exception $e){
            dd($e->getMessage());
            return Response()->json(['error' => 'Sorry something went worng. Please try again.']);
        }
        return Response()->json(['success' => $message]);
    }

    public function getUserData()
    {
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        return $user->id;
    }
    public function getUserComData()
    {
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        return $user->company_id;
    }


    // Method to fetch all truck loads of selected project
    public function getAllLoads($truckListId, $returnCollection=false, Request $request)
    {
        $this->load = new Loads();
        if($request->isMethod('post')){
            $masterData = Master::where('type', '<', 3)->withTrashed()->get();
            $query = '';
            $query .= (!empty($request->load_time)) ? "load_time LIKE '%".$request->load_time."%' AND ":'';
            $query .= (!empty($request->from_destination)) ? "from_destination LIKE '%".$request->from_destination."%' AND ":'';
            $query .= (!empty($request->to_destination)) ? "to_destination LIKE '%".$request->to_destination."%' AND ":'';
            $query .= (!empty($request->quantity)) ? "quantity LIKE '%".$request->quantity."%' AND ":'';

            if(!empty($request->las_master_data_load_id)){
                $load_id = Master::select(DB::raw('group_concat(id) as ids'))
                    ->where('type', 2)
                    ->whereRaw("value LIKE '%".$request->las_master_data_load_id."%'")->withTrashed()->get(['id'])->toArray();
                if(!empty($load_id[0]['ids'])){
                    $query .= "las_master_data.id IN(".$load_id[0]['ids'].") AND ";
                } else {
                    $query .= "las_master_data.id = '".$request->las_master_data_load_id."' AND ";
                }
            }
            if(!empty($request->las_master_data_volume_id)){
                $scale_id = Master::select(DB::raw('group_concat(id) as ids'))
                    ->where('type', 1)
                    ->whereRaw("value LIKE '%".$request->las_master_data_volume_id."%'")->withTrashed()->get(['id'])->toArray();
                if(!empty($scale_id[0]['ids'])){
                    $query .= "las_master_data.id IN(".$scale_id[0]['ids'].") AND ";
                } else {
                    $query .= "las_master_data.id = '".$request->las_master_data_volume_id."' AND ";
                }
            }
            $query = rtrim($query, 'AND ');
            if(!empty($query) && (!empty($request->las_master_data_load_id) || !empty($request->las_master_data_volume_id))){
                $query = 'INNER JOIN las_master_data ON las_master_data.id = las_truck_lists_loads.las_master_data_load_id OR las_master_data.id = las_truck_lists_loads.las_master_data_volume_id WHERE '.$query.' AND truck_list_id = '.$truckListId.'';
            } elseif(!empty($query) && empty($request->las_master_data_load_id) && empty($request->las_master_data_volume_id)) {
                $query = 'WHERE '.$query.' AND truck_list_id = '.$truckListId.'';
            } else {
                $query = 'WHERE truck_list_id = '.$truckListId.'';
            }
            $result = DB::select(DB::raw("SELECT las_truck_lists_loads.* FROM las_truck_lists_loads ".$query." "));
            foreach($result as $key1 => $val1){
                foreach($masterData as $key2 => $val2){
                    if($val1->las_master_data_load_id == $val2->id){
                        $result[$key1]->cargo_load = $val2->value;
                        $result[$key1]->cargo_load_desc = $val2->description;
                    }
                    if($val1->las_master_data_volume_id == $val2->id){
                        $result[$key1]->cargo_volume = $val2->value;
                        $result[$key1]->cargo_volume_desc = $val2->description;
                    }
                }
            }
            if($request->is_pdf){
                $path = storage_path('export');
                $fileName = 'truckLoads.pdf';
                $trucklistController = new TrucklistController();
                $truckListAllData = $trucklistController->getAllTruckList(true);
                $truckListData = $truckListAllData->where('id', $truckListId);
                foreach($truckListData as $key => $row){
                    $image_name = explode('uploads', $row->signature);
                    if(!empty($image_name[1])){
                        $truckListData[$key]['signature_image'] = $image_name['1'];
                    } else {
                        $truckListData[$key]['signature_image'] = '';
                    }
                }
                if(file_exists($path.'/'.$fileName)){
                    unlink($path.'/'.$fileName);
                }
                $trucklistImages = DB::table('las_truck_lists_images')->where('truck_list_id', $truckListId)->get();
                $company_info = Company::where('id', Auth::getUser()->company_id)->first();
                $pdf = PDF::loadView('admin.pdf', ['truckListData' => $truckListData, 'truckLoadData' => $result, 'trucklistImages' => $trucklistImages, 'company_info' => $company_info])
                    ->setPaper('a4')->setOrientation('landscape')
                    ->setOption('footer-html', 'Digital lassliste levert av lass.no')->save($path.'/'.$fileName);
                return $fileName;
            } else {
                return response()->json($result);
            }
        } else {
            $result = $this->load->index($truckListId);
            foreach($result as $key => $val){
                if(count($val->loadData) > 0){
                    $result[$key]['cargo_load'] = $val->loadData->value;
                    $result[$key]['cargo_load_desc'] = $val->loadData->description;
                } else {
                    $result[$key]['cargo_load'] = '';
                    $result[$key]['cargo_load_desc'] = '';
                }
                if(count($val->volumeData) > 0){
                    $result[$key]['cargo_volume'] = $val->volumeData->value;
                    $result[$key]['cargo_volume_desc'] = $val->volumeData->description;
                } else {
                    $result[$key]['cargo_volume'] = '';
                    $result[$key]['cargo_volume_desc'] = '';
                }
            }
        }
        if($returnCollection){
            return $result;
        }
        return response()->json($result);
    }
    // get specific load

    // Method to fetch master data base on type
    public function getMasterData($type)
    {
        $user_id = Auth::getUser()->id;
        return Response()->json(Master::where('type', $type)->where('user_id', $user_id)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveLoadData(Request $request)
    {
        //dd($request->all());
        $rules = [
            'load_time' => 'required',
            'from_destination' => 'required',
            'to_destination' => 'required',
            'las_master_data_load_id' => 'required',
            'las_master_data_volume_id' => 'required',
            'quantity' => 'required'
        ];
        $custom_message = [
            'load_time.required' => 'Load Time field is required.',
            'from_destination.required' => 'From Destination field is required.',
            'to_destination.required' => 'To Destination field is required.',
            'las_master_data_load_id.required' => 'Mass Type field is required.',
            'las_master_data_volume_id.required' => 'Scale field is required.',
            'quantity.required' => 'Quantity field is required.'
        ];
        $validator = Validator::make($request->all(), $rules, $custom_message);
        if($validator->fails()){
            return Response()->json(['error' => $validator->errors()->first()]);
        }
        try{
            $message = '';
            $dataArray = [
                'truck_list_id' => $request->truck_list_id,
                'load_time' => $request->load_time,
                'from_destination' => $request->from_destination,
                'to_destination' => $request->to_destination,
                'las_master_data_load_id' => $request->las_master_data_load_id,
                'las_master_data_volume_id' => $request->las_master_data_volume_id,
                'quantity' => $request->quantity,
                'notes' => $request->notes
            ];
            if($request->id){
                $result = Loads::where('id', $request->id)->update($dataArray);
                $message = 'Truck loads updated successfully!';
            } else {
                $result = Loads::create($dataArray);
                $message = 'Truck loads created successfully!';
            }
            return Response()->json(['success' => $message]);
        } catch(\Exception $e){
            return Response()->json(['error' => 'Sorry something went worng. Please try again.']);
        }
    }

    // Gett all truck list
    public function getAllTruckList() {

        $id = $this->getUserData();
        $this->trucklist = new Trucklist();
        $truckList = $this->trucklist->getTruckListLoad($id);
        foreach($truckList as $key => $val){
            if(count($val->getTruckLoads) > 0){
                $truckList[$key]['total_loads'] = count($val->getTruckLoads);
                $truckList[$key]['loads_data'] = $val->getTruckLoads;
            } else {
                $truckList[$key]['total_loads'] = 0;
                $truckList[$key]['loads_data'] = array();
            }
            if(count($val->getProjectData) > 0){
                $truckList[$key]['proj_name'] = $val->getProjectData->project_name;
            } else {
                $truckList[$key]['proj_name'] = '';
            }
        }

        return Response()->json( $truckList);
    }
    /**
     * Fetch Truck list.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTruckListByID($trucklistid)
    {
        $user_id =  $id = $this->getUserData();
       // return $user_id;
        $this->loads =  new Trucklist();
        $loadlist = $this->loads->getDriverLoads($user_id,$trucklistid);
        foreach($loadlist as $key => $val){
            if(count($val->getCustomer) > 0){
                $loadlist[$key]['customer_name'] = $val->getCustomer->customer_name;
            }else {
                $loadlist[$key]['customer_name'] = '';
            }
            if(count($val->getProject) > 0){
                $loadlist[$key]['project_name'] = $val->getProject->project_name;
            }else {
                $loadlist[$key]['project_name'] = '';
            }
            if(count($val->getCustomer) > 0){
                $loadlist[$key]['vehicle_name'] = $val->getVehicle->description;
            } else {
                $loadlist[$key]['vehicle_name'] = '';
            }
        }
        return response()->json(  $loadlist[0] );
    }
    // provide specific loads
    public function getLoadList($trucklistid, $loadid)
    {
        try{
            $user_id = $this->getUserData();
            $result = DB::table('las_truck_lists_loads')
                ->select('las_truck_lists_loads.*')
                ->join('las_truck_lists', 'las_truck_lists.id', '=', 'las_truck_lists_loads.truck_list_id')
                ->join('users', 'users.id', '=', 'las_truck_lists.user_id')
                ->where('users.id', $user_id)
                ->where('las_truck_lists_loads.id', $loadid)
                ->where('las_truck_lists_loads.truck_list_id', $trucklistid)
                ->first();
            return Response()->json($result);
        } catch (Exception $e){
            return Response()->json(['error', 'Some tihg went wronge please try again..']);
        }
    }
    // Delete record
    public function destroy($loadid, $trucklistid= false, Request $request)
    {
        if ($request->is('api/*') && !empty($this->getUserData()))
        {
            try{
                Loads::where('truck_list_id', $loadid)->delete();
                return Response()->json(array('success' => 'Record deleted successfully!'));
            } catch(\Exception $e){
                return Response()->json(['error' => 'Sorry something went worng. Try again.']);
            }
        }
        try{
            Loads::destroy($loadid);
            return Response()->json(array('success' => 'Record deleted successfully!'));
        } catch(\Exception $e){
            return Response()->json(['error' => 'Sorry something went worng. Please try again.']);
        }
    }
    // Method to download PDF file from system
    public function downloadPdf($name){
        $file= storage_path('export').'/'.$name;
        $headers = array(
            'Content-Type: application/pdf',
        );
        return response()->download($file, $name, $headers);
    }
}
