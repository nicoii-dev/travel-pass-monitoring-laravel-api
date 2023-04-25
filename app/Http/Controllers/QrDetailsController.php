<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QrDetails;

class QrDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|string|max:255',
        ]);
        $travel = QrDetails::where('user_id', $request->user_id)->where('status', '=', '1')->with('user', 'application')->first();
        return response()->json($travel, 200);
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
