<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\MedicalApplications;

class MedicalApplicationsController extends Controller
{
    public function index()
    {
        $medicalStatus = MedicalApplications::with('user')->get();
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'comment' => 'required|string|max:255',
        ]);

        if($validatedData){
           try {
                $code = Str::random(8);
                $medical = MedicalApplications::where('user_id', $request->user_id)->first();
                $medical->status = $request->status;
                $medical->reference_code = $code;
                $medical->comment = $request->comment;
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
    public function destroy(string $id)
    {
        //
    }
}
