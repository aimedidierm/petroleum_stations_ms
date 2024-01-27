<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role == 'admin') {
            $payments = Payment::latest()->get();
            $payments->load('user');
            return view('admin.payments', ['payments' => $payments]);
        } else {
            $payments = Payment::latest()->where('user_id', Auth::id())->get();
            return view('employee.payments', ['payments' => $payments]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'type' => 'required|string',
        ]);
        $payment = new Payment;
        $payment->amount = $request->amount;
        $payment->type = $request->type;
        $payment->status = 'payed';
        $payment->user_id = Auth::id();
        $payment->created_at = now();
        $payment->updated_at = null;
        $payment->save();
        return redirect('/employee/payments');
    }

    public function report(Request $request)
    {
        $request->validate([
            'status' => 'required|string|in:Cash,Momo,Card',
        ]);
        $payments = Payment::latest()->where('type', $request->status)->get();
        $payments->load('user');
        $pdf = Pdf::loadView('admin.payments_report', ['payments' => $payments]);
        return $pdf->download('report.pdf');
    }
}
