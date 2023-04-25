<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TravelPassApplications;
use Carbon\Carbon;
class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lsi = User::where('role', 'lsi')->get()->count();
        $admins = User::where('role', '!=', 'lsi')->where('role', '!=', 'admin')->get()->count();
        $approved = TravelPassApplications::where('status', '=', '1')->get()->count();
        $declined = TravelPassApplications::where('status', '=', '2')->get()->count();
        $pending = TravelPassApplications::where('status', '=', '0')->get()->count();
        $users = User::where('role', '!=', 'lsi')->where('role', '!=', 'admin')->get();
        // $applicationByMonth = TravelPassApplications::select('*')
        // ->whereBetween('created_at', [Carbon::now()->subMonth(12), Carbon::now()])
        // ->get();

        $applicationByMonth = TravelPassApplications::query()
        ->select(\DB::raw("count(*) as total, DATE_FORMAT(created_at, '%m') as month"))
        ->groupby('month')
        ->orderBy('month', 'ASC')
        ->get();

        return response()->json([
            'lsi' => $lsi, 
            'admin' => $admins, 
            'approved' => $approved, 
            'declined' => $declined, 
            'pending' => $pending,
            'users' => $users,
            'applicationByMonth' => $applicationByMonth
        ], 200);
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
