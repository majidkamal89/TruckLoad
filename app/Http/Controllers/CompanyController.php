<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class CompanyController extends Controller
{

    public function __construct()
    {
        $this->company = new Company();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('admin.company.index');
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
        if($request->file('logo')){
            $rules = [
                'company_name' => 'required|min:3',
                'logo' => 'image'
            ];
        } else {
            $rules = [
                'company_name' => 'required|min:3'
            ];
        }
        $custom_message = [
            'company_name.required' => 'Company Name field is required.'
        ];
        $validator = Validator::make($request->all(), $rules, $custom_message);
        if($validator->fails()){
            return Response()->json(['error' => $validator->errors()->first()]);
        }
        // File upload
        $logo = $request->logo;
        if($request->file('logo')){
            $file = array('logo' => $request->file('logo'));
            $destinationPath = base_path('public/uploads/'); // upload path
            $extension = $file['logo']->getClientOriginalExtension(); // getting image extension
            $fileName = $file['logo']->getClientOriginalName(); // renameing image
            $file['logo']->move($destinationPath, $fileName); // uploading file to given path
            $logo = $fileName;
        }
        //
        try{
            $message = '';
            if($request->id){
                $dataArray = [
                    'company_name' => $request->company_name,
                    'address' => $request->address,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip_postal' => $request->zip_postal,
                    'logo' => $logo,
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                $result = Company::where('id', $request->id)->update($dataArray);
                $message = 'Company information updated successfully!';
            } else {
                $dataArray = [
                    'company_name' => $request->company_name,
                    'address' => $request->address,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip_postal' => $request->zip_postal,
                    'logo' => $logo,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                $result = Company::create($dataArray);
                $message = 'Company created successfully!';
            }
            return Response()->json(['success' => $message]);
        } catch(\Exception $e){
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            Company::where('id', $id)->delete();
            return Response()->json(array('success' => 'Company deleted successfully!'));
        } catch(\Exception $e){
            return Response()->json(['error' => 'Sorry something went worng. Please try again.']);
        }
    }


    public function loadData(Request $request)
    {
        if($request->is('api/*')){
            $result = $this->company->getAllData();
        } else {
            $company_id = Auth::getUser()->company_id;
            $result = $this->company->getUserCompanyData($company_id);
        }
        foreach($result as $key => $data){
            if(count($data->companyUser) > 0){
                $result[$key]['companyUser'] = $data->companyUser->id;
            } else {
                $result[$key]['companyUser'] = 0;
            }
        }
        return Response()->json($result);
    }
}
