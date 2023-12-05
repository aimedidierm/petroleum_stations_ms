<?php

namespace App\Http\Controllers;

use App\Models\BlankCard;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = User::where('role', 'employee')->get();
        $data->load('address');
        $blankCards = BlankCard::latest()->get();
        return view('admin.employees', ['employees' => $data, 'blankCards' => $blankCards]);
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
            'name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'province' => 'required|string',
            'district' => 'required|string',
            'sector' => 'required|string',
            'cell' => 'required|string',
            'village' => 'required|string',
            'card' => 'required|string'
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->card = $request->card;
        $user->password = 'password';
        $user->save();
        $userAddress = new UserAddress();
        $userAddress->province = $request->province;
        $userAddress->district = $request->district;
        $userAddress->sector = $request->sector;
        $userAddress->cell = $request->cell;
        $userAddress->village = $request->village;
        $userAddress->user_id = $user->id;
        $userAddress->save();
        $card = BlankCard::where('card', $request->card)->first();
        $card->delete();
        return redirect('/admin/employees');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        if (Auth::user()->role == 'admin') {
            $request->validate([
                'card' => 'required|string|unique:users,card',
                'name' => 'required|string',
                'userId' => 'required|numeric'
            ]);
            $user = User::find($request->userId);
            if ($user) {
                $user->name = $request->name;
                $user->card = $request->card;
                $user->update();
                return redirect('/admin/employees');
            } else {
                return back()->withErrors('User not found not match');
            }
        } else {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email',
                'phone' => 'required|numeric',
                'password' => 'required|string',
                'confirmPassword' => 'required|string'
            ]);
            $user = User::find(Auth::id());
            if ($request->password == $request->confirmPassword) {
                $user->name = $request->name;
                $user->email = $request->email;
                $user->phone = $request->phone;
                $user->password = $request->password;
                $user->update();
                if (Auth::user()->role == 'admin') {
                    return redirect('/admin/settings');
                } else {
                    return redirect('/employee/settings');
                }
            } else {
                return back()->withErrors('Passwords not match');
            }
        }
    }

    public function adminUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'password' => 'required|string',
            'confirmPassword' => 'required|string'
        ]);
        $user = User::find(Auth::id());
        if ($request->password == $request->confirmPassword) {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = $request->password;
            $user->update();
            return redirect('/admin/settings');
        } else {
            return back()->withErrors('Passwords not match');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $id)
    {
        if ($id) {
            $userAddress = UserAddress::where('user_id', $id->id);
            $userAddress->delete();
            $id->delete();
            return redirect('/admin/employees');
        } else {
            return back()->withErrors('User not found');
        }
    }
}
