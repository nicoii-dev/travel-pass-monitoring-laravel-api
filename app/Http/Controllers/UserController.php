<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\TeamInvitation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\CurrentAddress;
use App\Models\PermanentAddress;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('role', '!=', 'admin')->with('currentAddress', 'permanentAddress')->get();
        return response()->json($users, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'dob' => 'required|string|max:255',
            'civil_status' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'current_street' => 'required|string|max:255',
            'current_barangay' => 'required|string|max:255',
            'current_city' => 'required|string|max:255',
            'current_province' => 'required|string|max:255',
            'current_region' => 'required|string|max:255',
            'current_zipcode' => 'required|string|max:255',
            // 'permanent_street' => 'required|string|max:255',
            // 'permanent_barangay' => 'required|string|max:255',
            // 'permanent_city' => 'required|string|max:255',
            // 'permanent_province' => 'required|string|max:255',
            // 'permanent_region' => 'required|string|max:255',
            // 'permanent_zipcode' => 'required|string|max:255',
        ]);
        if($validatedData){
           try {
                $password = Str::random(8);
                $user = new User();
                $user->first_name = $request->first_name;
                $user->middle_name = $request->middle_name;
                $user->last_name = $request->last_name;
                $user->gender = $request->gender;
                $user->civil_status = $request->civil_status;
                $user->dob = $request->dob;
                $user->phone_number = $request->phone_number;
                $user->email = $request->email;
                $user->is_verified = 1;
                $user->status = 1;
                $user->password = bcrypt($password);
                $user->role = $request->role;
                $user->save();

                $current_address = new CurrentAddress();
                $current_address->user_id = $user->id;
                $current_address->street = $request->current_street;
                $current_address->barangay = $request->current_barangay;
                $current_address->city_municipality = $request->permanent_city;
                $current_address->province = $request->current_province;
                $current_address->region = $request->current_region;
                $current_address->zipcode = $request->current_zipcode;
                $current_address->save();

                // $permanent_address = new PermanentAddress();
                // $permanent_address->user_id = $user->id;
                // $permanent_address->street = $request->permanent_street;
                // $permanent_address->barangay = $request->permanent_barangay;
                // $permanent_address->city_municipality = $request->permanent_city;
                // $permanent_address->province = $request->permanent_province;
                // $permanent_address->region = $request->permanent_region;
                // $permanent_address->zipcode = $request->permanent_zipcode;
                // $permanent_address->save();

                Mail::to($user->email)->send(new TeamInvitation($user, $password, $request->role));

                return response()->json(['message' => 'User created successfully.'], 200);
                }catch(\Exception $e)
           {
              DB::rollBack();
              return response()->json(throw $e);
           }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $schedule = User::where('id', $id)->with('currentAddress', 'permanentAddress')->first();
        return response()->json($schedule, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'civil_status' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'dob' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'current_street' => 'required|string|max:255',
            'current_barangay' => 'required|string|max:255',
            'current_city' => 'required|string|max:255',
            'current_province' => 'required|string|max:255',
            'current_region' => 'required|string|max:255',
            'current_zipcode' => 'required|string|max:255',
            // 'permanent_street' => 'required|string|max:255',
            // 'permanent_barangay' => 'required|string|max:255',
            // 'permanent_city' => 'required|string|max:255',
            // 'permanent_province' => 'required|string|max:255',
            // 'permanent_region' => 'required|string|max:255',
            // 'permanent_zipcode' => 'required|string|max:255',
        ]);
        if($validatedData){
            try {
                $user = User::find($id);
                $user->first_name = $request->first_name;
                $user->middle_name = $request->middle_name;
                $user->last_name = $request->last_name;
                $user->civil_status = $request->civil_status;
                $user->gender = $request->gender;
                $user->dob = $request->dob;
                $user->phone_number = $request->phone_number;
                $user->role = $request->role;
                $user->save();

                $current_address = CurrentAddress::where('user_id', $user->id)->first();
                $current_address->street = $request->current_street;
                $current_address->barangay = $request->current_barangay;
                $current_address->city_municipality = $request->current_city;
                $current_address->province = $request->current_province;
                $current_address->region = $request->current_region;
                $current_address->zipcode = $request->current_zipcode;
                $current_address->save();

                // $permanent_address = PermanentAddress::where('user_id', $user->id)->first();
                // $permanent_address->street = $request->permanent_street;
                // $permanent_address->barangay = $request->permanent_barangay;
                // $permanent_address->city_municipality = $request->permanent_city;
                // $permanent_address->province = $request->permanent_province;
                // $permanent_address->region = $request->permanent_region;
                // $permanent_address->zipcode = $request->permanent_zipcode;
                // $permanent_address->save();

                return response()->json(['message' => 'User updated successfully.'], 200);
                }catch(\Exception $e)
            {
               DB::rollBack();
               return response()->json(throw $e);
            }
         }
    }

    public function activateUser($id)
    {
        $user = User::find($id);
        $user->status = 1;
        $user->save();
        return response()->json(['message' => 'User activated successfully'], 200);
    }

    public function deactivateUser($id)
    {
        $user = User::find($id);
        $user->status = 0;
        $user->save();
        return response()->json(['message' => 'User deactivated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
