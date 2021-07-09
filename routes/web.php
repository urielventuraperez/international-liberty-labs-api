<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// Unauth routes
$router->post('/login','UserController@login');
$router->post('/super-admin/register','UserController@superadmin_register');
$router->get('result/pdf/{folio}', 'ResultController@generatePdfReport');

//Auth routes middleware
$router->group(['prefix' => 'api', 'middleware' => ['auth', 'client']], function () use ($router) {
    // Dashboard
    $router->get('/dashboard', 'DashboardController@index');

    // Users
    $router->post('/register','UserController@register');
    $router->post('/logout','UserController@logout');
    $router->post('/update/password','UserController@updatePassword');
    $router->post('/update/info','UserController@updateProfile');
    $router->delete('/user/{id}','UserController@deleteUser');
    $router->post('/user/{id}','UserController@activeUser');
    $router->get('/users','UserController@getUsers');
    $router->get('/me','UserController@profile');

    // Patients
    $router->get('/patients', 'PatientController@index');
    $router->get('/patients/{id}/tests', 'PatientController@index');
    $router->get('patients/{id}', 'PatientController@show');
    $router->get('/patient/find', 'PatientController@findPatientByEmail');
    $router->get('/patients/find', 'PatientController@findPatientsByEmail');
    $router->post('/patients','PatientController@create');
    $router->post('/patients/{id}','PatientController@update');
    $router->delete('/patients/{id}','PatientController@delete');

    // Tests
    // View test
    $router->get('/patients/{id}/tests', 'TestController@byPatient');
    // Create test
    $router->post('/patients/{patientId}/tests','TestController@create');
    $router->get('/tests', 'TestController@index');
    $router->get('/tests/search', 'TestController@findTestByConditions');
    $router->get('/tests/{folio}', 'TestController@show');
    $router->post('/tests/{id}','TestController@update');
    $router->delete('/tests/{id}','TestController@delete');

    // Results
    //Create result
    $router->post('/tests/{id}/results','ResultController@create');
    $router->get('/results', 'ResultController@index');
    $router->get('results/{id}', 'ResultController@show');
    
    $router->post('/results/{id}','ResultController@update');
    $router->delete('/results/{id}','ResultController@delete');

    // Roles
    $router->get('/roles','RoleController@index');
});
