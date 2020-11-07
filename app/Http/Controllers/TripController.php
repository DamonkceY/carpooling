<?php

namespace App\Http\Controllers;

use App\Trip;
use App\TripUsers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TripController extends Controller
{
    public function validateTrip(Request $request){
        $request->validate([
            'departure_place' => 'required|string',
            'arrival_place' => 'required|string',
            'departure_datetime' => 'required|date_format:Y-m-d H:i:s',
            'contact' => 'required|regex:/()[0-9]{8}/',
            'available_places' => 'required|numeric'
        ]);

        return response()->json([
            'message' => 'success'
        ],201);
    }
    public function createTrip(Request $request)
    {
        $trip = new Trip([
            'departplace' => $request->departure_place,
            'arrivalplace' => $request->arrival_place,
            'departuredatetime' => $request->departure_datetime,
            'contact' => $request->contact,
            'avplaces' => $request->available_places
        ]);

        $trip->user()->associate($request->user());
        $trip->save();

        return response()->json([
            'message' => 'Successfully created trip!'
        ], 201);
    }

    public function deleteTrip(Request $request)
    {
        if (Trip::find($request->id)->delete()) {
            return response()->json([
                'message' => 'Successfully deleted trip!'
            ], 201);
        }
    }

    public function updateTrip(Request $request)
    {
        $data =  [
            'departplace' => $request->departPlace,
        ];
        if (Trip::find($request->id)->update($data)) {
            return response()->json([
                'message' => 'Successfully updated trip!'
            ], 201);
        }
    }

    public function joinTrip(Request $request){
        $trip = Trip::find($request->trip_id);
        if($trip->avplaces>0){
            $trip->avplaces--;
            $trip->save();

            $applied = new TripUsers([
                'trip_id'=>$request->trip_id
            ]);
            $applied->user()->associate($request->user());
            $applied->trip()->associate($trip);
            $applied->save();

            return response()->json([
                'message' => 'Successfully Applied to trip!',
                'appliedTrip' => $applied
            ], 201);
        }else{
            return response()->json([
                'message' => 'Error: Maximum number exceeded'
            ], 401);
        }
    }
}
