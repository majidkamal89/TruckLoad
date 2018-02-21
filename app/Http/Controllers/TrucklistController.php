<?php

namespace App\Http\Controllers;

use App\Company;
use App\Customer;
use App\Loads;
use App\Master;
use App\Project;
use App\Trucklistimage;
use App\User;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use App\Trucklist;
use App\Http\Controllers\LoadController;
use League\Flysystem\Exception;
use PDF;

class TrucklistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.trucklist.index');
    }

    /**
     * Fetch Truck list.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllTruckList($returnCollection=false)
    {
        $user_id = array(Auth::getUser()->id);
        if(Auth::getUser()->user_type == 2){
            $users = User::where('company_id', Auth::getUser()->company_id)->where('user_type', 3)->get(['id']);
            $userArray = $users->toArray();
            $user_id = [];
            foreach($userArray as $item){
                $user_id[] = $item['id'];
            }
        }
        $this->truck = new Trucklist();
        $trucklist = $this->truck->index($user_id);
        foreach($trucklist as $key => $val){
            if(count($val->getCustomer) > 0){
                $trucklist[$key]['customer_name'] = $val->getCustomer->customer_name;
            } else {
                $trucklist[$key]['customer_name'] = '';
            }
            if(count($val->getProject) > 0){
                $trucklist[$key]['project_name'] = $val->getProject->project_name;
                $trucklist[$key]['project_code'] = $val->getProject->project_code;
            } else {
                $trucklist[$key]['project_name'] = '';
                $trucklist[$key]['project_code'] = '';
            }
            if(count($val->getVehicle) > 0){
                $trucklist[$key]['vehicle_name'] = $val->getVehicle->value;
            } else {
                $trucklist[$key]['vehicle_name'] = '';
            }
        }
        if($returnCollection){
            return $trucklist;
        }
        return response()->json($trucklist);
    }

    /**
     * Fetch all customers.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllCustomers(Request $request)
    {
        $this->loadCtrl = new LoadController();
        if ($request->is('api/*') && !empty($this->loadCtrl->getUserData()))
        {
            $company_id = $this->loadCtrl->getUserComData();
            $users = User::where('company_id', $company_id)->where('user_type', 2)->first(['id']);
            $user_id = $users->id;
        }
        else
            $user_id = Auth::getUser()->id;
        return Response()->json(Customer::where('user_id', $user_id)->get());
    }

    /**
     * Fetch all projects.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllProjects($customer_id)
    {
        return Response()->json(Project::where('customer_id', $customer_id)->get());
    }

    /**
     * Fetch all vehicles.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllVehicles($project_id = false, Request $request)
    {
        $this->loadCtrl = new LoadController();
        if ($request->is('api/*') && !empty($this->loadCtrl->getUserComData()))
        {
             $company_id = $this->loadCtrl->getUserComData();
             $users = User::where('company_id', $company_id)->where('user_type', 2)->first(['id']);
             $user_id = $users->id;
        }
        else
            $user_id = Auth::getUser()->id;
        return Response()->json(Master::where('user_id', $user_id)->where('type', 3)->get());
    }

    /**
     * Fetch all drivers of company.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllDrivers()
    {
        return Response()->json(User::where('company_id', Auth::getUser()->company_id)->where('user_type', 3)->get());
    }

    /**
     * Fetch all loads from master data.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLoads(Request $request)
    {
        $this->loadCtrl = new LoadController();
        if ($request->is('api/*') && !empty($this->loadCtrl->getUserData())) {
            $company_id = $this->loadCtrl->getUserComData();
            $users = User::where('company_id', $company_id)->where('user_type', 2)->first(['id']);
            $user_id = $users->id;
            return Response()->json(Master::where('user_id', $user_id)->where('type', 2)->get());
        } else
            return Response()->json(['error' => 'Sorry something went worng. Please try again.']);
    }
    /**
     * Fetch all Volum from master data.
     *
     * @return \Illuminate\Http\Response
     */
    public function getVolum(Request $request)
    {
        $this->loadCtrl = new LoadController();
        if ($request->is('api/*') && !empty($this->loadCtrl->getUserData())) {
            $company_id = $this->loadCtrl->getUserComData();
            $users = User::where('company_id', $company_id)->where('user_type', 2)->first(['id']);
            $user_id = $users->id;
            return Response()->json(Master::where('user_id', $user_id)->where('type', 1)->get());
        } else
            return Response()->json(['error' => 'Sorry something went worng. Please try again.']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // For api call getting user id;
        $this->loadCtrl = new LoadController();
        if ($request->is('api/*') && !empty($this->loadCtrl->getUserData()))
        {
            $request->request->add(['user_id' => $this->loadCtrl->getUserData()]);
        }
        $rules = [
            'customer_id' => 'required',
            'project_id' => 'required',
            'vehicle_id' => 'required',
            'user_id' => 'required'
        ];
        $custom_message = [
            'customer_id.required' => 'Customer Name field is required.',
            'project_id.required' => 'Project Name field is required.',
            'vehicle_id.required' => 'Vehicle Name field is required.',
            'user_id.required' => 'Driver Name field is required.'
        ];
        $validator = Validator::make($request->all(), $rules, $custom_message);
        if($validator->fails()){
            return Response()->json(['error' => $validator->errors()->first()]);
        }
        $request->request->add(['created_at' => date('Y-m-d H:i:s')]);
        $request->request->add(['updated_at' => date('Y-m-d H:i:s')]);
        try{
            if($request->id){
                $trucklist_id = $request->id;
            } else {
                $trucklist_id = 0;
            }
            $date = date('Y-m-d');
            $if_exist = Trucklist::where('customer_id', $request->customer_id)
                ->where('project_id', $request->project_id)
                ->where('vehicle_id', $request->vehicle_id)
                ->where('user_id', $request->user_id)
                ->whereDate('created_at', '=', $date)
                ->where('id', '<>', $trucklist_id)
                ->get();
            $message = '';
            if($request->id){
                $dataArray = [
                    'customer_id' => $request->customer_id,
                    'project_id' => $request->project_id,
                    'vehicle_id' => $request->vehicle_id,
                    'signature' => $request->signature,
                    'user_id' => $request->user_id,
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                if(count($if_exist)){
                    return Response()->json(['error' => 'Same Truck list already exist for today.']);
                }
                $result = Trucklist::where('id', $request->id)->update($dataArray);
                $message = 'Truck list updated successfully!';
            } else {
                if(count($if_exist)){
                    return Response()->json(['error' => 'Same Truck list already exist for today.']);
                }
                $result = Trucklist::create($request->all());
                $message = 'Truck list created successfully!';
            }
            return Response()->json(['success' => $message]);
        } catch(\Exception $e){
            dd($e->getMessage());
            return Response()->json(['error' => 'Sorry something went worng. Please try again.']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Create Truck list PDF.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function createPdf($id)
    {
        $truckListAllData = $this->getAllTruckList(true);
        if($id == "all"){
            $truckListData = $truckListAllData;
        } else {
            $id = explode(',', $id);
            $truckListData = $truckListAllData->whereIn('id', $id);
        }
        foreach($truckListData as $key => $row){
            $image_name = explode('uploads', $row->signature);
            if(!empty($image_name[1])){
                $truckListData[$key]['signature_image'] = $image_name['1'];
            } else {
                $truckListData[$key]['signature_image'] = '';
            }
        }
        $this->loads = new Loads();
        if($id == "all"){
            $truckLoadData = $this->loads->getAllData(false);
        } else {
            $truckLoadData = $this->loads->getAllData($id);
        }
        foreach($truckLoadData as $key => $val){
            if(count($val->loadData) > 0){
                $truckLoadData[$key]['cargo_load'] = $val->loadData->value;
                $truckLoadData[$key]['cargo_load_desc'] = $val->loadData->description;
            } else {
                $truckLoadData[$key]['cargo_load'] = '';
                $truckLoadData[$key]['cargo_load_desc'] = '';
            }
            if(count($val->volumeData) > 0){
                $truckLoadData[$key]['cargo_volume'] = $val->volumeData->value;
                $truckLoadData[$key]['cargo_volume_desc'] = $val->volumeData->description;
            } else {
                $truckLoadData[$key]['cargo_volume'] = '';
                $truckLoadData[$key]['cargo_volume_desc'] = '';
            }
        }
        if($id == "all"){
            $trucklistImages = DB::table('las_truck_lists_images')->get();
        } else {
            $trucklistImages = DB::table('las_truck_lists_images')->whereIn('truck_list_id', $id)->get();
        }

        $company_info = Company::where('id', Auth::getUser()->company_id)->first();
        $pdf = PDF::loadView('admin.pdf', ['truckListData' => $truckListData, 'truckLoadData' => $truckLoadData, 'trucklistImages' => $trucklistImages, 'company_info' => $company_info])
            ->setPaper('a4')->setOrientation('landscape')
            ->setOption('footer-html', 'Digital lassliste levert av lass.no');
        return $pdf->download('Trucklist.pdf');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        // Delete api data
        $this->loadCtrl = new LoadController();
        if ($request->is('api/*') && !empty($this->loadCtrl->getUserData()))
        {
            $user_id = $this->loadCtrl->getUserData();
            try{
                Trucklist::where('id', $id)->where('user_id', $user_id)->delete();
                Loads::where('truck_list_id', $id)->delete();
                return Response()->json(array('success' => 'Record deleted successfully!'));
            } catch(\Exception $e){
                return Response()->json(['error' => 'Sorry something went worng. Try again.']);
            }
        }
        try{
            Trucklist::where('id', $id)->delete();
            Loads::where('truck_list_id', $id)->delete();
            return Response()->json(array('success' => 'Record deleted successfully!'));
        } catch(\Exception $e){
            return Response()->json(['error' => 'Sorry something went worng. Please try again.']);
        }
    }

    // update truck list with api route
    public function update(Request $request, $id)
    {
        $this->loadCtrl = new LoadController();
        $user_id = $this->loadCtrl->getUserData();
        $rules = array(
            'customer_id' => 'required',
            'project_id' => 'required',
            'vehicle_id' => 'required'
        );
        $credentials = $request->all();
        $validation = Validator($credentials, $rules);
        if ($validation->fails()){
            return response()->json($validation->messages(), 500);
        }
        try {
            $loads = Trucklist::find($id);
            if(! $loads)
            {
                return response()->json(['message' , 'User not found'], 404 );
            }
            Trucklist::where('id', $id)->where('user_id', $user_id)->update($credentials);
            $message = 'Record Updated successfully!';
        } catch(\Exception $e){
            return Response()->json(['error' => 'Sorry something went worng. Please try again.']);
        }
        return Response()->json(['success' => $message]);
    }

    // Upload Trucklist Signature and Attachments
    public function storeAttachment(Request $request){
        $rules = [
            'logo' => 'image',
            'attachment_type' => 'required'
        ];
        $custom_message = [
            'logo.required' => 'Image field is required.',
            'attachment_type.required' => 'Attachment type field is required.'
        ];
        $validator = Validator::make($request->all(), $rules, $custom_message);
        if($validator->fails()){
            return Response()->json(['error' => $validator->errors()->first()]);
        }
        // File upload
        $logo = $path = '';
        if($request->file('logo')){
            $file = array('logo' => $request->file('logo'));
            $destinationPath = base_path('public/uploads/'.$request->id .'/'); // upload path
            $extension = $file['logo']->getClientOriginalExtension(); // getting image extension
            $fileName = 'attachment-'.$request->id.'-'.date_timestamp_get(date_create()).'.'.$extension; // renameing image
            $file['logo']->move($destinationPath, $fileName); // uploading file to given path
            if($request->attachment_type == 'signature'){
                $logo = url('uploads/').'/'.$fileName;
            } else {
                $path = url('uploads/').'/';
                $logo = $fileName;
            }
        }
        try{
            $message = '';
            if($request->attachment_type == 'signature'){
                $dataArray = [
                    'signature' => $logo
                ];
                $result = Trucklist::where('id', $request->id)->update($dataArray);
                $message = 'Truck list Signature updated successfully!';
            } else {
                $dataArray = [
                    'truck_list_id' => $request->id,
                    'image_name' => $logo,
                    'image_path' => $path
                ];
                $result = Trucklistimage::create($dataArray);
                $message = 'Truck list image created successfully!';
            }
            return Response()->json(['success' => $message]);
        } catch(\Exception $e){
            dd($e->getMessage());
            return Response()->json(['error' => 'Sorry something went worng. Please try again.']);
        }

    }

    public function storeSignatureFront(Request $request){
        $rules = [
            'logo' => 'required',
            'attachment_type' => 'required'
        ];
        $custom_message = [
            'logo.required' => 'Image field is required.',
            'attachment_type.required' => 'Attachment type field is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $custom_message);
        if($validator->fails()){
            return Response()->json(['error' => $validator->errors()->first()]);
        }

        // File upload
        $logo = $path = '';

       $fileO = $this->base64_to_jpeg($request->logo,public_path('uploads/'.$request->id ), public_path('uploads/'.$request->id .'/'.'signature-'.$request->id.'.jpg'));

        if($fileO){
            $file = array('logo' => $fileO);
           // uploading file to given path
            if($request->attachment_type == 'signature'){
                $logo =  url('uploads/').$request->id .'/'.'signature-'.$request->id.'.jpg'; //url('uploads/').'/'.'signature.jpg';
            }
        }
        try{
            $message = '';
            if($request->attachment_type == 'signature'){
                $dataArray = [
                    'signature' => $logo
                ];
                $result = Trucklist::where('id', $request->id)->update($dataArray);
                $message = 'Truck list Signature updated successfully!';
            }
            return Response()->json(['success' => $message]);
        } catch(\Exception $e){
            dd($e->getMessage());
            return Response()->json(['error' => 'Sorry something went worng. Please try again.']);
        }



    }

    public function base64_to_jpeg($base64_string,$dirPath, $output_file) {
        // open the output file for writing

        if(!is_dir($dirPath)){
         mkdir($dirPath, 0777, true);
        }
        $ifp = fopen( $output_file, 'wb' );

        // split the string on commas
        // $data[ 0 ] == "data:image/png;base64"
        // $data[ 1 ] == <actual base64 string>
        $data = explode( ',', $base64_string );

        // we could add validation here with ensuring count( $data ) > 1
        fwrite( $ifp, base64_decode( $data[ 1 ] ) );

        // clean up the file resource
        fclose( $ifp );

        return $output_file;
    }

    // get all images by truck list id
    public function getAllImagesById($truckListId)
    {
        $this->loadCtrl = new LoadController();
        $user_id = $this->loadCtrl->getUserData();
        $this->trucklist = new Trucklist();
        $imageList = $this->trucklist->getAllImages($truckListId, $user_id);
        return response()->json(  $imageList );
    }
    // delete signature
    public function delSignature($truckListID)
    {
        $this->loadCtrl = new LoadController();
        $user_id = $this->loadCtrl->getUserData();
        try{
            Trucklist::where('id', $truckListID)->where('user_id', $user_id)->update(array('signature' => null));
            unlink( public_path('uploads/'.$truckListID .'/'.'signature-'.$truckListID.'.jpg'));
            return Response()->json(['sucess' => 'Signature deleted sucessfully..']);
        } catch ( Exception $e){
            return Response()->json(['error' => 'Sorry something went worng. Please try again.']);
        }
    }
    // delete attachements
    public function delAttachements($truckListID)
    {
        try{
            Trucklistimage::where('truck_list_id', $truckListID)->delete();
            return Response()->json(['sucess' => 'Attachements deleted sucessfully..']);
        } catch ( Exception $e){
            return Response()->json(['error' => 'Sorry something went worng. Please try again.']);
        }
    }
}
