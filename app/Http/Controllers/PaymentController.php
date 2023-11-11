<?php

namespace App\Http\Controllers;

use App\Models\Payment;
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
        $request->validate([
            'amount' => 'required|numeric',
        ]);
        $payment = new Payment;
        $payment->amount = $request->amount;
        $payment->status = 'payed';
        $payment->user_id = Auth::id();
        $payment->created_at = now();
        $payment->updated_at = null;
        $payment->save();
        return redirect('/employee/payments');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
