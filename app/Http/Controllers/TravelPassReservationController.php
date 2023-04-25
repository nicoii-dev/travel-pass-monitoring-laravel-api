<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TravelPassReservations;
use App\Models\TravelPassApplications;
use App\Models\Schedule;
use App\Models\MedicalApplications;

class TravelPassReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservation = TravelPassReservations::with('user', 'schedule')->get();
        return response()->json($reservation, 200);
    }

    public function getUserSchedule()
    {
        $schedule = TravelPassReservations::where('user_id', Auth::user()->id)->with('schedule')->get();
        return response()->json($schedule, 200);
    }

    public function setUserToAppointed(string $id)
    {
        try {
            $reservation = TravelPassReservations::find($id);
            $reservation->status = 2;
            $reservation->save();

            return response()->json(['message' => 'Verified successfully.'], 200);
            }catch(\Exception $e)
        {
            DB::rollBack();
            return response()->json(throw $e);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'schedule_id' => 'required|string|max:255',
            'date_of_travel' => 'required|date|max:255',
            'travel_type' => 'required|string|max:255',
        ]);
        if($validatedData){
           try {
                // filter if the user has an approve medical application
                $medical_count = MedicalApplications::where('user_id', Auth::user()->id)->where('status', '=', "1")->get()->count();
                if($medical_count < 1) {
                    return response()->json(['message' => "You don't have any approved medical applications yet"], 422);
                }
                // filter if the max lsi is already full
                $schedule_count = TravelPassReservations::where('schedule_id', $request->schedule_id)->get()->count();
                $schedule_info = Schedule::find($request->schedule_id);
                if($schedule_count == (int)$schedule_info->max_lsi) {
                    return response()->json(['message' => 'Selected slot is already full'], 422);
                }
                // filter if the user have a active QR
                $activeQr = TravelPassApplications::where('user_id', Auth::user()->id)->where('status', '=', "1")->get();
                if(count($activeQr) > 0) {
                    return response()->json(['message' => 'You already have an active QR Code'], 422);
                }
                // filter if the user has already a schedule for this day
                $schedule_info = TravelPassReservations::where('user_id', Auth::user()->id)->where('status', '=', 1)->get()->count();
                if($schedule_info > 0) {
                    return response()->json(['message' => 'You already have an active schedule'], 422);
                } else {
                    $code = Str::random(8);
                    $reservation = new TravelPassReservations();
                    $reservation->user_id = Auth::user()->id;
                    $reservation->schedule_id = $request->schedule_id;
                    $reservation->reference_code = $code;
                    $reservation->status = 1;
                    $reservation->save();
                    // updating selected schedule current_lsi count
                    $schedule_info = Schedule::find($request->schedule_id);
                    $schedule_info->current_lsi = $schedule_count + 1;
                    $schedule_info->save();
                    // saving travel pass application
                    $application = new TravelPassApplications();
                    $application->user_id = Auth::user()->id;
                    $application->reservation_id = $reservation->id;
                    $application->travel_type = $request->travel_type;
                    $application->date_of_travel = $request->date_of_travel;
                    $application->status = 0;

                    $application->origin_region = $request->origin_region;
                    $application->origin_province = $request->origin_province;
                    $application->origin_city = $request->origin_city;
                    $application->origin_barangay = $request->origin_barangay;
                    $application->origin_street = $request->origin_street;
                    $application->origin_zipcode = $request->origin_zipcode;

                    $application->destination_region = $request->destination_region;
                    $application->destination_province = $request->destination_province;
                    $application->destination_city = $request->destination_city;
                    $application->destination_barangay = $request->destination_barangay;
                    $application->destination_street = $request->destination_street;
                    $application->destination_zipcode = $request->destination_zipcode;

                    $application->remarks = $request->remarks;
                    $application->save();
    
                    return response()->json(['message' => 'Reservation successfully created', ], 200);
                }

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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
