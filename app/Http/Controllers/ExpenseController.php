<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenses = Expense::latest()->get();
        return view('admin.expenses', ['expenses' => $expenses]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'name' => 'required|string',
        ]);
        $payment = new Expense;
        $payment->amount = $request->amount;
        $payment->name = $request->name;
        $payment->created_at = now();
        $payment->updated_at = null;
        $payment->save();
        return redirect('/admin/expenses');
    }

    public function report()
    {
        $expenses = Expense::latest()->get();
        $pdf = Pdf::loadView('admin.expenses_report', ['expenses' => $expenses]);
        return $pdf->download('report.pdf');
    }

    public function destroy(Expense $id)
    {
        if ($id) {
            $id->delete();
            return redirect('/admin/expenses');
        } else {
            return back()->withErrors('Expense not found');
        }
    }
}
