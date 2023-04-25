<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\MedicalApplications;
use Illuminate\Support\Facades\Auth;

class MedicalApplicationsController extends Controller
{
    public function index()
    {
        $medicalStatus = MedicalApplications::with('user')->with('appointment')->get();
        return response()->json($medicalStatus, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'appointment_id' => 'required|string|max:255',
            'user_id' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'comment' => 'required|string|max:255',
        ]);

        if($validatedData){
           try {
                $code = Str::random(8);
                $medical = new MedicalApplications();
                $medical->appointment_id = $request->appointment_id;
                $medical->user_id = $request->user_id;
                $medical->status = $request->status;
                $medical->reference_code = $code;
                $medical->comment = $request->comment;
                $medical->save();

                return response()->json(['message' => 'Medical application created successfully.'], 200);
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
        $application = MedicalApplications::find($id)->with('user', 'user.currentAddress', 'appointment')->first();
        return response()->json($application, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'status' => 'required|string|max:255',
            'remarks' => 'required|string|max:255',
        ]);

        if($validatedData){
           try {
                $code = Str::random(8);
                $medical = MedicalApplications::find($id);
                $medical->status = $request->status;
                $medical->reference_code = $code;
                $medical->comment = $request->remarks;
                $medical->save();

                return response()->json(['message' => 'Medical application updated successfully.'], 200);
                }catch(\Exception $e)
           {
              DB::rollBack();
              return response()->json(throw $e);
           }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function getUserMedicalApplication(Request $request)
    {
        $medical = MedicalApplications::where('user_id', Auth::user()->id)->with('user', 'appointment')->get();
        return response()->json($medical, 200);
    }
}
