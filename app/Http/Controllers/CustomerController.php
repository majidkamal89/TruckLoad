<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class CustomerController extends Controller
{

    public function __construct()
    {
        $this->customer = new Customer();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('admin.customer.index');
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
        $user_id = Auth::getUser()->id;
        $rules = [
            'customer_name' => 'required|min:3'
        ];
        $custom_message = [
            'customer_name.required' => 'Customer Name field is required.'
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
                    'customer_name' => $request->customer_name,
                    'customer_address' => $request->customer_address,
                    'signature' => $request->signature,
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                $result = Customer::where('id', $request->id)->update($dataArray);
                $message = 'Customer information updated successfully!';
            } else {
                $result = Customer::create($request->all());
                $message = 'Customer created successfully!';
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
            Customer::where('id', $id)->delete();
            return Response()->json(array('success' => 'Customer deleted successfully!'));
        } catch(\Exception $e){
            return Response()->json(['error' => 'Sorry something went worng. Please try again.']);
        }
    }

    /**
     * This method use to fetch all Customers data.
     *
     */
    public function loadData()
    {
        $user_id = Auth::getUser()->id;
        return Response()->json(Customer::where('user_id', $user_id)->get());
    }
}
