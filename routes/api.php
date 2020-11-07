<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->post('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});



Route::group(['middleware' => 'auth:api'], function () {
    Route::post('updateAvatar', 'UserController@updateAvatar');
    Route::post('updateUser', 'UserController@update');
    Route::post('updatePassword', 'UserController@updatePassword');
    Route::post('confirmUpdatePassword', 'UserController@confirmUpdatePassword');
    Route::get('getUserTrips', 'UserController@getUserTrips');
    //
    Route::post('validateTrip', 'TripController@validateTrip');
    Route::post('createTrip', 'TripController@createTrip');
    Route::post('updateTrip', 'TripController@updateTrip');
    Route::delete('deleteTrip', 'TripController@deleteTrip');
    Route::post('joinTrip', 'TripController@joinTrip');

    // Route::get('getTrip', function (Request $request) {
    //     return response()->json([
    //         'message' => 'Sussess',
    //         'trip' => App\Trip::where('id', $request->id)->with('user')->get()
    //     ]);
    // });
    // 
    Route::get('getAllTrips', function () {
        return response()->json([
            'trips' => App\Trip::with('user')->get()
        ], 201);
    });
    // 
    // Route::get('getUserTrips', function (Request $request) {
    //     return response()->json([
    //         'message' => 'Success',
    //         'userTrips' => App\Trip::where('user_id', $request->user()->id)->with('user')->get()
    //     ]);
    // });
    Route::get('getUserAppliedTrip', function (Request $request) {
        $appliedtrips = App\TripUsers::where('user_id', $request->user()->id)->with('trip')->get();
        return response()->json([
            'message' => 'Success',
            'appliedTrip' => $appliedtrips
        ]);
    });
});
