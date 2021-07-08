<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
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
        $roles = Role::all();

        if ($roles) {
            return response([
                'status' => true,
                'message' => '',
                'data' => $roles
            ]);
        } else {
          return response([
            'status' => false,
            'message' => 'Not roles found',
            'data' => []
        ]);
        }
    }

}
