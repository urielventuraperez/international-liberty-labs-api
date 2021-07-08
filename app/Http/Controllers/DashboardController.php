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
        $patient = Patient::all();
        $attendingToday = Patient::whereDate('created_at', Carbon::today())->get();
        $test = Test::all();
        $result = Result::all();

        return response([
            'status'=>true,
            'message'=>'',
            'data'=>[
                'patients' => $patient->count(),
                'attendingToday' => $attendingToday->count(),
                'tests' => $test->count(),
                'results' => $result->count()
            ]
        ]);
    }
}
