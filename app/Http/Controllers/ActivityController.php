<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->get('date', now()->toDateString());
    
        // Aktivitas tanggal terpilih
        $activities = Activity::whereDate('log_date', $selectedDate)
            ->orderBy('log_time', 'asc')
            ->get();
    
        $score = $activities->sum('score');
    
        // Data kalender bulanan
        $month = Carbon::parse($selectedDate);
    
        $calendarData = Activity::select(
                DB::raw('DATE(log_date) as date'),
                DB::raw('SUM(score) as total')
            )
            ->whereMonth('log_date', $month->month)
            ->whereYear('log_date', $month->year)
            ->groupBy('date')
            ->get()
            ->keyBy('date');
    
        return view('dashboard', compact(
            'activities',
            'score',
            'calendarData',
            'selectedDate',
            'month'
        ));
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'activity' => 'required|string|max:255',
            'score' => 'required|in:1,0,-1',
        ]);

        Activity::create([
            'activity' => $request->activity,
            'score' => $request->score,
            'log_date' => now()->toDateString(),
            'log_time' => now()->toTimeString(),
        ]);

        return back();
    }

    public function destroy($id)
{
    Activity::findOrFail($id)->delete();
    return back();
}

}
