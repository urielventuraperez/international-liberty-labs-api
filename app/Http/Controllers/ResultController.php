<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Result;
use App\Models\Test;
use Illuminate\Support\Str;
use \Barryvdh\DomPDF\Facade as PDF;
use Validator;
use Illuminate\Http\Response;

class ResultController extends Controller
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
        $results = Result::paginate(20);

        if ($results) {
            return response([
                'message' => '',
                'data' => $results
            ], 200);
        } else {
          return response([
            'message' => 'doesn´t exist registers',
            'data' => [],
          ], 200);
        }
    }

    public function show($id) {
        $result = Result::findOrFail($id);
        if ($result) {
            return response(['message' => '', 'data' => $result], 200);
        } else {
            return response(['message' => 'doesn´t exist the register', 'data'=>[]], 200);
        }
    }

    public function create($id, Request $request) {
        $test = Test::find($id);
        $validator = Validator::make($request->all(), [
            'outcome' => 'required|max:255',
            'reference' => 'required|max:255',
        ]);

        if($validator->fails()){
            return response(['data' => [], 'message' =>  $validator->errors()], 422);
        }

        $input = $request->all();
        $uuid = (string) Str::uuid();
        $input['uuid'] = $uuid;

        $result = new Result();
        $result->folio = $input['uuid'];
        $result->outcome = $input['outcome'];
        $result->reference = $input['reference'];
        $result->result = $input['result'];

        if( $test->result()->save($result) ) {
            return response(['message' => 'Register successfully created!', 'data'=>$result], 200);
        } else {
            return response(['message' => 'Server error, try again!', 'data'=>[]], 502);
        }
    }

    public function update(Request $request, $id) {
        $result = Result::find($id);

        $result->outcome = $request['outcome'] ?? $test->outcome;
        $result->reference = $request['reference'] ?? $test->reference;
        $result->result = $request['result'] ?? $test->result;
        $result->send_report = $request['send_report'] ?? $test->send_report;
        $result->delivered = $request['delivered'] ?? $test->delivered;

        if(!$result->save()) {
            return response(['message' => 'retry again, cannot update the test', 'data'=>[]], 422);
        }

        return response(['message' => 'Test successfully updated!', 'data'=>$result], 200);        

    }

    public function delete($id) {
        $result = Result::findOrFail($id);

        if(!$result->delete()) {
            return response(['message' => 'retry again, cannot delete the register', 'data'=>[]], 422);
        }

        return response(['message' => 'Register successfully deleted!', 'data'=>[]], 200);
    }

    public function generatePdfReport($folio)
    {
        $result = Result::with('test')->where('folio', $folio)->first();
        if(!$result) {
            return response(['data'=>[]]);
        }
        
//        return response(['data'=>$result->test->testType]);
        
        $data = [
            'patient' => $result->test->patient->name .' '.$result->test->patient->last_name,
            'date_of_birth' => $result->test->patient->date_of_birth,
            'created_test' => $result->test->created_at,
            'created_result' => $result->created_at,
            'test' => $result->test->testType->name .' '. $result->test->testType->description,
            'outcome' => $result->outcome,
            'reference' => $result->reference,
            'result' => $result->result ? 'Positive' : 'Negative',
        ];

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
        ->loadView('pdf.report', $data);
        return $pdf->download('Reporte '. $result->test->folio .'.pdf');
    }

    public function sendByEmail()
    {

    }

}
