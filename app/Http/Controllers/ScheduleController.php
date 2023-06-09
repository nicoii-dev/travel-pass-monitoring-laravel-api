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

    public function getScheduleByDate(Request $request)
    {
        $request->validate(['schedule_date' => 'required|date|max:255']);

        $schedules = Schedule::where('schedule_date', '=', $request->schedule_date)->get();
        return response()->json($schedules, 200);
    }

    public function medicalSchedules(Request $request)
    {
        $request->validate(['schedule_date' => 'required|date|max:255']);
        $schedules = Schedule::where('schedule_type', '=', 'medical')->where('schedule_date', '=', $request->schedule_date)->get();
        return response()->json($schedules, 200);
    }

    public function travelPassSchedules(Request $request)
    {
        $request->validate(['schedule_date' => 'required|date|max:255']);
        $schedules = Schedule::where('schedule_type', '=', 'travelpass')->where('schedule_date', '=', $request->schedule_date)->get();
        return response()->json($schedules, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'schedule_type' => 'required|string|max:255',
            'schedule_date' => 'required|date|max:255',
            'schedule_time' => 'required|string|max:255',
            'max_lsi' => 'required|string|max:255',
        ]);
        if($validatedData){
            $schedule = Schedule::where('schedule_type', $request->schedule_type)
            ->where('schedule_date', '=', $request->schedule_date)
            ->where('schedule_time', '=', $request->schedule_time)
            ->first();
            if($schedule != null) {
                return response()->json(['message' => 'This schedule is already created.'], 422);
            }
           try {
                $schedule = new Schedule();
                $schedule->schedule_type = $request->schedule_type;
                $schedule->schedule_date = $request->schedule_date;
                $schedule->schedule_time = $request->schedule_time;
                $schedule->max_lsi = $request->max_lsi;
                $schedule->current_lsi = 0;
                $schedule->save();

                return response()->json(['message' => 'Schedule created successfully.'], 200);
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
            'schedule_type' => 'required|string|max:255',
            'schedule_date' => 'required|date|max:255',
            'schedule_time' => 'required|string|max:255',
            'max_lsi' => 'required|string|max:255',
        ]);
        if($validatedData){
            $schedule = Schedule::where('schedule_type', $request->schedule_type)
            ->where('schedule_date', $request->schedule_date)
            ->where('schedule_time', $request->schedule_time)
            ->where('id', '!=', $id)->first();
            if($schedule !== null) {
                return response()->json(['message' => 'This schedule is already created.'], 422);
            }
           try {
                $schedule = Schedule::find($id);
                $schedule->schedule_type = $request->schedule_type;
                $schedule->schedule_date = $request->schedule_date;
                $schedule->schedule_time = $request->schedule_time;
                $schedule->max_lsi = $request->max_lsi;
                $schedule->save();

                return response()->json(['message' => 'Schedule updated successfully.'], 200);
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
    public function destroy($id)
    {
        if(DB::table("schedules")->where('id',$id)->delete()){
            return response()->json(['message' => 'Schedule successfully deleted.'], 200);
        }else{
            return response()->json(['message' => 'Delete unsuccessful'], 500);
        }
    }
}
