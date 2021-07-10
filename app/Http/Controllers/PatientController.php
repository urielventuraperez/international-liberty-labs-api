<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;

use Validator;

class PatientController extends Controller
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
        $patients = Patient::all();

        if ($patients) {
            return response([
                'message' => '',
                'data' => $patients
            ], 200);
        } else {
          return response([
            'message' => 'doesn´t exist registers',
            'data' => [],
          ], 200);
        }
    }

    public function show($id) {
        $patient = Patient::with('tests')->findOrFail($id);
        if ($patient) {
            return response(['message' => '', 'data' => $patient], 200);
        } else {
            return response(['message' => 'doesn´t exist the register', 'data'=>[]], 200);
        }
    }

    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email'
        ]);

        if($validator->fails()){
            return response(['data' => [], 'message' =>  $validator->errors()], 422);
        }      

        $input = $request->all();
        
        $verifyPatient = Patient::where('email', $input['email'])->first();  

        $patient = new Patient();
        $patient = $patient::create($input);

        return response(['status' => 200, 'message' => 'Register successfully created!', 'data'=>$patient], 200);
    }

    public function update(Request $request, $id) {
        $patient = Patient::find($id);

        $patient->name = $request['name'] ?? $patient->name;
        $patient->last_name = $request['last_name'] ?? $patient->last_name;
        $patient->email = $request['email'] ?? $patient->email;
        $patient->phone = $request['phone'] ?? $patient->phone;
        $patient->cell_phone = $request['cell_phone'] ?? $patient->cell_phone;
        $patient->date_of_birth = $request['date_of_birth'] ?? $patient->date_of_birth;

        if(!$patient->save()) {
            return response(['status' => 422, 'message' => 'retry again, cannot update the patient', 'data'=>[]], 422);
        }

        return response(['status' => 200, 'message' => 'Patient successfully updated!', 'data'=>$patient], 200);        

    }

    public function delete($id) {
        $patient = Patient::findOrFail($id);

        if($patient) {
            $patient->tests()->delete();
            $patient->delete();
            return response(['status' => 200, 'message' => 'Register successfully deleted!', 'data'=>[]], 200);
        }
        return response(['status' => 422, 'message' => 'retry again, cannot delete the register', 'data'=>[]], 422);
    }

    public function findPatientByEmail(Request $request) {
        $patient = Patient::where('email', $request->query('email'))->first();

        if(!$patient){
            return response(['status' => 200, 'message' => 'The patient is not registered.', 'data'=>[]]);
        }

        return response([
            'status' => 200, 
            'message' => 'Patient is registered, continue with the test.', 
            'data'=>$patient], 200);
    }

    public function findPatientsByEmail(Request $request) {
        $patient = Patient::where('email', 'like', '%' . $request->query('email') . '%')->get();

        return response([
            'status' => 200, 
            'message' => 'Searching patients...', 
            'data'=>$patient], 200);
    }
}
