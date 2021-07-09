<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Test;
use App\Models\Result;
use Carbon\Carbon;

class DashboardController extends Controller
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
        $patient = Patient::all()->count();
        $attendingToday = Test::whereDate('created_at', Carbon::today())->count();
        $test = Test::count();
        $result = Result::count();

        $pcrTotal = Test::where('test_type_id', 2)->count();
        $antigenTotal = Test::where('test_type_id', 1)->count();

        $negativesTest = Result::where('result', false)->count();
        $positivesTest = Result::where('result', true)->count();

        return response([
            'status'=>true,
            'message'=>'',
            'data'=>[
                'patients' => $patient,
                'attendingToday' => $attendingToday,
                'tests' => $test,
                'results' => $result,
                'pcr' => $pcrTotal,
                'antigen' => $antigenTotal,
                'negatives' => $negativesTest,
                'positives' => $positivesTest
            ]
        ]);
    }
}
