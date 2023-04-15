<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\MedicalReservation;

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
            'user_id' => 'required|string|max:255',
            'schedule_id' => 'required|string|max:255',
        ]);
        if($validatedData){
           try {
                $code = Str::random(8);
                $reservation = new MedicalReservation();
                $reservation->user_id = $request->user_id;
                $reservation->schedule_id = $request->schedule_id;
                $reservation->reference_code = $code;
                $reservation->save();

                return response()->json(['success' => 'Medical reservation created successfully.'], 200);
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
        $schedule = MedicalReservation::find($id);
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
