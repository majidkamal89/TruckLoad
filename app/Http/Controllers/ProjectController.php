<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($customer_id)
    {
        $customer = Customer::where('id', $customer_id)->withTrashed()->first(['customer_name']);
        $customer_name = $customer->customer_name;
        return View::make('admin.project.index', compact('customer_id', 'customer_name'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
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
        $user_id = Auth::getUser()->id;
        $rules = [
            'project_name' => 'required'
        ];
        $custom_message = [
            'project_name.required' => 'Project Name field is required.'
        ];
        $validator = Validator::make($request->all(), $rules, $custom_message);
        if($validator->fails()){
            return Response()->json(['error' => $validator->errors()->first('customer_name')]);
        }
        $request->request->add(['user_id' => $user_id]);
        $request->request->add(['created_at' => date('Y-m-d H:i:s')]);
        $request->request->add(['updated_at' => date('Y-m-d H:i:s')]);
        try{
            $message = '';
            if($request->id){
                $dataArray = [
                    'project_name' => $request->project_name,
                    'project_code' => $request->project_code,
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                $result = Project::where('id', $request->id)->update($dataArray);
                $message = 'Project information updated successfully!';
            } else {
                $result = Project::create($request->all());
                $message = 'Project created successfully!';
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
        return Response()->json(Project::where('customer_id', $id)->get());
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
            Project::where('id', $id)->delete();
            return Response()->json(array('success' => 'Project deleted successfully!'));
        } catch(\Exception $e){
            return Response()->json(['error' => 'Sorry something went worng. Please try again.']);
        }
    }
}
