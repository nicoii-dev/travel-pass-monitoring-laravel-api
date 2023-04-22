<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\MedicalReservation;
use App\Models\Schedule;

class MedicalReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservation = MedicalReservation::with('user', 'schedule')->get();
        return response()->json($reservation, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'schedule_id' => 'required|string|max:255',
        ]);
        if($validatedData){
           try {
                // filter if the max lsi is already full
                $schedule_count = MedicalReservation::where('schedule_id', $request->schedule_id)->get()->count();
                $schedule_info = Schedule::find($request->schedule_id);
                if($schedule_count == (int)$schedule_info->max_lsi) {
                    return response()->json(['message' => 'Selected slot is already full'], 200);
                }
                // filter if the user has already a schedule for this day
                $schedule_info = MedicalReservation::where('user_id', Auth::user()->id)->where('status', '=', 1)->with('schedule')->get()->count();
                if($schedule_info > 0) {
                    return response()->json(['message' => 'You already have an active schedule'], 422);
                } else {
                    $code = Str::random(8);
                    $reservation = new MedicalReservation();
                    $reservation->user_id = Auth::user()->id;
                    $reservation->schedule_id = $request->schedule_id;
                    $reservation->reference_code = $code;
                    $reservation->status = 1;
                    $reservation->save();
                    // updating selected schedule current_lsi count
                    $schedule_info = Schedule::find($request->schedule_id);
                    $schedule_info->current_lsi = $schedule_count + 1;
                    $schedule_info->save();
    
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
        $schedule = MedicalReservation::find($id)->with('user', 'schedule')->get();
        return response()->json($schedule, 200);
    }

    public function getUserSchedule()
    {
        $schedule = MedicalReservation::where('user_id', Auth::user()->id)->with('schedule')->get();
        return response()->json($schedule, 200);
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
