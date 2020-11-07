<?php

namespace App\Http\Controllers;

use App\Trip;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'birthDate' => 'required|date_format:Y-m-d|before:today',
            'city' => 'required|string',
        ]);
        $data = $request->all();
        if (User::find($request->user()->id)->update($data)) {
            return response()->json([
                'message' => [
                    'userUpdated' => ['Successfully updated user!']
                ],
                'user' => User::find($request->user()->id),
            ], 201);
        }
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_confirmation' => 'required',
            'oldPassword' => 'required|string',
            'password' => 'required|string|confirmed',
        ]);

        $user = $request->user();
        if ($request->oldPassword === $request->password) {
            return response()->json([
                'errors' => [
                    'password' => ['It\'s the same password -_-']
                ]
            ], 422);
        }

        if (!Hash::check($request->oldPassword, $user->password)) {
            return response()->json([
                'errors' => [
                    'oldPassword' => ['Please verify your old Password !']
                ]
            ], 422);
        }
    }

    public function confirmUpdatePassword(Request $request)
    {
        $user = $request->user();
        $user->password = Hash::make($request->password);
        $user->save();
        $user->token()->revoke();
        return response()->json([
            'message' => 'Successfully updated password please login again !'
        ], 201);
    }

    public function getUserTrips(Request $request)
    {
        return response()->json([
            'user' => User::with('trips')->where('id', $request->user()->id)->get()
        ]);
    }

    public function updateAvatar(Request $request)
    {

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = $request->user();

        $avatarName = $user->id . '_avatar' . time() . '.' . request()->avatar->getClientOriginalExtension();

        $request->avatar->storeAs('avatars', $avatarName);

        $user->avatar = $avatarName;

        $user->save();

        return response()->json([
            'message' => [
                'updatedAvatar' => ['Successfully changed the profile picture']
            ],
            'user' => $user
        ], 201);
    }
}
