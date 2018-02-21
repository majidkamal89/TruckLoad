<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Master;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MasterController extends Controller
{
    /**
     * Display Scale blade view.
     *
     * @return \Illuminate\Http\Response
     */
    public function scaleIndex()
    {
        return view('admin.masterpages.index_scale');
    }

    /**
     * Display Mass Type blade view.
     *
     * @return \Illuminate\Http\Response
     */
    public function massTypeIndex()
    {
        return view('admin.masterpages.index_mass_type');
    }

    /**
     * Display Vehicle blade view.
     *
     * @return \Illuminate\Http\Response
     */
    public function vehicleIndex()
    {
        return view('admin.masterpages.index_vehicle');
    }

    /**
     * Display a listing of the type Scale.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllSettings($type)
    {
        if(Auth::getUser()->user_type == 1) {
            return Response()->json(Master::where('type', $type)->withTrashed()->get());
        } else {
            return Response()->json(Master::where('type', $type)->where('user_id', Auth::getUser()->id)->withTrashed()->get());
        }
    }

    /**
     * Show the company page resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showCompany()
    {
        return view('admin.masterpages.company');
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
        $field_name = '';
        if($request->type == 1){$field_name = 'Scale';}
        if($request->type == 2){$field_name = 'Load';}
        if($request->type == 3){$field_name = 'Vehicle Name';}
        $rules = [
            'value' => 'required'
        ];
        $custom_message = [
            'value.required' => 'The '.$field_name.' field is required.',
        ];
        $validator = Validator::make($request->all(), $rules, $custom_message);
        if($validator->fails()){
            return Response()->json(['error' => $validator->errors()->first()]);
        }
        if($request->id){
            $if_exist = Master::where('user_id', $user_id)->where('id', '!=', $request->id)->where('value', $request->value)->count();
        } else {
            $if_exist = Master::where('user_id', $user_id)->where('value', $request->value)->count();
        }

        if($if_exist){
            return Response()->json(['error' => 'The '.$field_name.' has already been taken.']);
        }
        $request->request->add(['user_id' => $user_id]);
        try{
            $message = '';
            if($request->id){
                $dataArray = [
                    'value' => $request->value,
                    'description' => $request->description
                ];
                $result = Master::where('id', $request->id)->update($dataArray);
                $message = 'Record updated successfully!';
            } else {
                $result = Master::create($request->all());
                $message = 'Record created successfully!';
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
            Master::destroy($id);
            return Response()->json(array('success' => 'Record deleted successfully!'));
        } catch(\Exception $e){
            return Response()->json(['error' => 'Sorry something went worng. Please try again.']);
        }
    }
}
