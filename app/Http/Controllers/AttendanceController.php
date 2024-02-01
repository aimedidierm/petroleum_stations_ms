<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role == 'admin') {
            $employee = Attendance::get();
            $employee->load('employee');
            return view('admin.dashboard', ['employees' => $employee]);
        } else {
            $attendance = Attendance::where('user_id', Auth::id())->get();
            return view('employee.attendance', ['attendances' => $attendance]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
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
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        //
    }

    public function report(Request $request)
    {
        $reportDate = $request->reportDate;
        [$year, $month] = explode('-', $reportDate);
        $employees = Attendance::whereYear('created_at', '=', $year)
            ->whereMonth('created_at', '=', $month)
            ->get();
        $employees->load('employee');
        // return view('admin.attendance_report', ['employees' => $employees]);
        $pdf = Pdf::loadView('admin.attendance_report', ['employees' => $employees])->setPaper('a2', 'landscape');
        return $pdf->download('report.pdf');
    }
}
