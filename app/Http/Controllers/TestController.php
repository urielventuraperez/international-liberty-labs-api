<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Patient;
use Illuminate\Support\Str;

use Validator;

class TestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index() {
        $tests = Test::all();

        if ($tests) {
            return response([
                'message' => '',
                'status' => 200,
                'data' => $tests
            ], 200);
        } else {
          return response([
             'status' => 200,
            'message' => 'doesn´t exist registers',
            'data' => [],
          ], 200);
        }
    }

    public function show($folio) {
        $test = Test::with(['paymentMethod', 'testType', 'patient', 'result'])->where('folio', $folio)->first();
        if ($test) {
            return response(['status' => 200, 'message' => '', 'data' => $test], 200);
        } else {
            return response(['status' => 200, 'message' => 'doesn´t exist the register', 'data'=>[]], 200);
        }
    }

    public function create($patientId, Request $request) {
        $patient = Patient::find($patientId);
        
        $validator = Validator::make($request->all(), [
            'flight_time' => 'required|date',
        ]);

        if($validator->fails()){
            return response(['data' => [], 'message' =>  $validator->errors()], 422);
        }

        $input = $request->all();
        $uuid = (string) Str::uuid();
        $input['folio'] = $uuid;

        $test = new Test();
        
        $test->notify_by_email = $input['notify_by_email'] ?? true;
        $test->flight_time = $input['flight_time'];
        $test->test_type_id = $input['test_type_id'];
        $test->payment_method_id = $input['payment_method_id'];
        $test->folio = $input['folio'];

        if( $patient->tests()->save($test) ) {
            return response(['status'=> 200, 'message' => 'Register successfully created!', 'data'=>$test], 200);
        } else {
            return response(['status' => 502, 'message' => 'Server error, try again!', 'data'=>[]], 502);
        }
   
    }

    public function update(Request $request, $id) {
        $test = Test::find($id);

        $test->notify_by_email = $request['notify_by_email'] ?? $test->notify_by_email;
        $test->flight_time = $request['flight_time'] ?? $test->flight_time;
        $test->test_type_id = $request['test_type_id'] ?? $test->test_type_id;
        $test->payment_method_id = $request['payment_method_id'] ?? $test->payment_method_id;
        $test->patient_id = $request['patient_id'] ?? $test->patient_id;

        if(!$test->save()) {
            return response(['message' => 'retry again, cannot update the test', 'data'=>[]], 502);
        }

        return response(['message' => 'Test successfully updated!', 'data'=>$test], 200);        

    }

    public function delete($id) {
        $test = Test::findOrFail($id);

        if(!$test->delete()) {
            return response(['message' => 'retry again, cannot delete the register', 'data'=>[]], 502);
        }

        return response(['message' => 'Register successfully deleted!', 'data'=>[]], 200);
    }

    public function byPatient($id) {
        return Test::where('patient_id', '=', $id);
    }

    public function findTestByConditions(Request $request) {
        $folio = $request->has('folio') ? $request->query('folio') : '';
        $flight_time = $request->has('flight_time') ? $request->query('flight_time') : '';
        $created_at = $request->has('created_at') ? $request->query('created_at') : '';
        $test = Test::where('folio', 'like', '%' . $folio . '%')
                        ->orWhere('created_at', '=', $created_at)
                        ->orWhere('flight_time', '=', $flight_time)
                        ->get();

        return response([
            'status' => 200, 
            'message' => 'Test was filter succesfuly.', 
            'data'=>$test], 200);
    }

}
