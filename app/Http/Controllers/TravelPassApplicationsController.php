<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TravelPassApplications;

class TravelPassApplicationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $applications = TravelPassApplications::with('user', 'reservation')->get();
        return response()->json($applications, 200);
    }


    public function getUserQr(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|string|max:255',
        ]);
        $travel = TravelPassApplications::where('user_id', $request->user_id)->first();
        return response()->json($travel, 200);
    }

    public function getUserApplication(Request $request)
    {
        $validatedData = $request->validate([
            'reservation_id' => 'required|string|max:255',
        ]);
        $travel = TravelPassApplications::where('reservation_id', $request->reservation_id)->with('user', 'user.currentAddress', 'reservation')->first();
        return response()->json($travel, 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
     
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $application = TravelPassApplications::find($id)->with('user', 'user.currentAddress')->first();
        return response()->json($application, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'status' => 'required|string|max:255',
        ]);

        if($validatedData){
           try {
                $travel = TravelPassApplications::find($id);
                $travel->status = $request->status;
                $travel->save();
                if($request->status == 1) {
                    return response()->json(['message' => 'Travel Pass Application approved successfully.'], 200);
                }
                return response()->json(['message' => 'Travel Pass Application declined successfully.'], 200);
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
