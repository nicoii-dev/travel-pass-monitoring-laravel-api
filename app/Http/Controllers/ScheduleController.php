<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Schedule;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schedules = Schedule::all();
        return response()->json($schedules, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'schedule_date' => 'required|date|max:255',
            'schedule_time' => 'required|string|max:255',
            'max_lsi' => 'required|string|max:255',
        ]);
        if($validatedData){
           try {
                $schedule = new Schedule();
                $schedule->schedule_date = $request->schedule_date;
                $schedule->schedule_time = $request->schedule_time;
                $schedule->max_lsi = $request->max_lsi;
                $schedule->save();

                return response()->json(['success' => 'Schedule created successfully.'], 200);
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
        $schedule = Schedule::find($id);
        return response()->json($schedule, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'schedule_date' => 'required|date|max:255',
            'schedule_time' => 'required|string|max:255',
            'max_lsi' => 'required|string|max:255',
        ]);
        if($validatedData){
           try {
                $schedule = Schedule::find($id);
                $schedule->schedule_date = $request->schedule_date;
                $schedule->schedule_time = $request->schedule_time;
                $schedule->max_lsi = $request->max_lsi;
                $schedule->save();

                return response()->json(['success' => 'Schedule updated successfully.'], 200);
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
