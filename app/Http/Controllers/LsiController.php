<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class LsiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('role', 'lsi')->with('currentAddress', 'permanentAddress')->get();
        return response()->json($users, 200);
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
    public function show(string $id)
    {
        $users = User::where('id', $id)->where('role', 'lsi')->with('currentAddress', 'permanentAddress')->first();
        return response()->json($users, 200);
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
