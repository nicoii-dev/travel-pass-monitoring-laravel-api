<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Mail\VerifyEmail;
use App\Models\User;
use App\Models\CurrentAddress;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'civil_status' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'dob' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'current_street' => 'required|string|max:255',
            'current_barangay' => 'required|string|max:255',
            'current_city' => 'required|string|max:255',
            'current_province' => 'required|string|max:255',
            'current_region' => 'required|string|max:255',
            'current_zipcode' => 'required|string|max:255',

            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6',
        ]);
        if($validatedData){
           try {
                DB::beginTransaction();
                $user = new User();
                $user->first_name = $request->first_name;
                $user->middle_name = $request->middle_name;
                $user->last_name = $request->last_name;
                $user->civil_status = $request->civil_status;
                $user->gender = $request->gender;
                $user->phone_number = $request->phone_number;
                $user->dob = $request->dob;
                $user->role = $request->role;
                $user->is_verified = 0;
                $user->status = 1;
                $user->email = $request->email;
                $user->password = bcrypt($request->password);
                $user->save();

                $current_address = new CurrentAddress();
                $current_address->user_id = $user->id;
                $current_address->street = $request->current_street;
                $current_address->barangay = $request->current_barangay;
                $current_address->city_municipality = $request->current_city;
                $current_address->province = $request->current_province;
                $current_address->region = $request->current_region;
                $current_address->zipcode = $request->current_zipcode;
                $current_address->save();

                $token = $user->createToken('MyApp')->plainTextToken;
                $link = "http://localhost:3000/verify/$user->email/token=$token";
                Mail::to($user->email)->send(new VerifyEmail($user, $link));
                DB::commit();
                return response()->json([
                    "message" => "Register successfully"
                ], 200);
            }catch(\Exception $e)
           {
              DB::rollBack();
              return response()->json(throw $e);
           }
        }
    }

    public function login(Request $request) {

        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // Check email
        $user = User::where('email', $fields['email'])->first();

        // Check password
        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Incorrect username or password.'
            ], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;
        if($user->is_verified == "1") {
            if($user->status == "1") {
                $response = [
                    'user' => $user,
                    'token' => $token,
                ];
            } else {
                return response(['message' => 'Account deactivated. Please contact administrator.'], 401);
            }
        } else {
            return response(['message' => 'Email is not verified.'], 401);
        }
        return response($response);
    }

    public function logout ()
    {
        if(isset(Auth::user()->id)){
            Auth::user()->tokens()->where('id', Auth::user()->currentAccessToken()->id)->delete();
            return response()->json([
                'status' => true
            ], 200);
        }else{
            return response()->json([
                'status' => false
            ], 401);
        }
    }

    public function verifyToken(Request $request)
    {
        if(isset(Auth::user()->id)){
            return response()->json([
                'status' => true
            ], 200);
        }else{
            return response()->json([
                'status' => false
            ], 401);
        }
    }

    public function verifyEmail(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
        ]);
        $user = User::where('email', $fields['email'])->first();
        if($user){
            $user->is_verified = true;
            $user->email_verified_at = now();
            $user->save();
            return response()->json([
                'message' => "Email verified"
            ], 200);
        }else{
            return response()->json([
                'message' => "Email not found"
            ], 404);
        }

    }

    public function changePassword(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'current_password' => 'required',
            'new_password' => 'required',
        ]);

        // Check email
        $user = User::where('email', $fields['email'])->first();

        // Check password
        if(!$user || !Hash::check($fields['current_password'], $user->password)) {
            return response([
                'message' => 'Incorrect current password.'
            ], 401);
        }

        $user->password = Hash::make($fields['new_password']);
        $user->save();

        return response()->json(["message" => "Password updated successfully"], 200);
    }
}
