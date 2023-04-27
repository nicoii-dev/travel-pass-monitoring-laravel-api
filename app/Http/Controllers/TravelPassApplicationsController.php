<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TravelPassApplications;
use App\Models\QrDetails;
use Illuminate\Support\Facades\Auth;

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
        $travel = TravelPassApplications::where('user_id', $request->user_id)->where('status', '=', '1')->with('user', 'reservation')->first();
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
    public function getUserTravelApplication(Request $request)
    {
        $travel = TravelPassApplications::where('user_id', Auth::user()->id)->with('user', 'reservation')->get();
        return response()->json($travel, 200);
    }

    /**
     * Display the specified resource.
     */

     public function show(string $id)
     {
         $application = TravelPassApplications::where('id', $id)->with('user', 'user.currentAddress')->first();
         return response()->json($application, 200);
     }

    public function viewTravelpass(string $id)
    {
        $application = TravelPassApplications::where('id', $id)->where('status', '=', '1')->with('user', 'user.currentAddress')->first();
        return response()->json($application, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function approve(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'remarks' => 'required|string|max:255',
            'start_date' => 'required|date|max:255',
            'end_date' => 'required|date|max:255'
        ]);

        if($validatedData){
           try {
                $code = Str::random(8);
                $travel = TravelPassApplications::find($id);
                $travel->status = $request->status;
                $travel->reference_code = $code;
                $travel->remarks = $request->remarks;
                $travel->save();

                $qrDetails = new QrDetails();
                $qrDetails->user_id = $request->user_id;
                $qrDetails->application_id = $id;
                $qrDetails->start_date = $request->start_date;
                $qrDetails->end_date = $request->end_date;
                $qrDetails->reference_code = $code;
                $qrDetails->status = 1;
                $qrDetails->remarks = $request->remarks;
                $qrDetails->save();

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

    public function decline(string $id)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'remarks' => 'required|string|max:255',
            'start_date' => 'required|date|max:255',
            'end_date' => 'required|date|max:255'
        ]);

        if($validatedData){
           try {
                $travel = TravelPassApplications::find($id);
                $travel->status = $request->status;
                $travel->remarks = $request->remarks;
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
