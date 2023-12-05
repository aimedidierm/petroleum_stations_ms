<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceIn;
use App\Models\AttendanceOut;
use App\Models\BlankCard;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HardwareController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'card' => 'required|string',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'card_allowed' => false,
                'message' => $validator->errors()->all(),
            ], 422);
        }
        $user = User::where('card', $request->card)->first();

        if ($user) {
            $currentDate = now()->toDateString();
            $timeIn = AttendanceIn::where('user_id', $user->id)
                ->whereDate('time', $currentDate)
                ->first();

            if ($timeIn) {
                $timeOut = AttendanceOut::where('user_id', $user->id)
                    ->whereDate('time', $currentDate)
                    ->first();

                if (!$timeOut) {
                    $newTimeOut = new AttendanceOut;
                    $newTimeOut->time = now();
                    $newTimeOut->user_id = $user->id;
                    $newTimeOut->save();
                    $updateAttendance = Attendance::where('user_id', $user->id)->latest()->first();
                    $updateAttendance->time_out = $newTimeOut->time;
                    $updateAttendance->updated_at = now();
                    $updateAttendance->update();
                    return response()->json([
                        'card_allowed' => true,
                        'message' => 'Time out Recorded',
                    ], 200);
                } else {
                    return response()->json([
                        'card_allowed' => false,
                        'message' => 'You left work today',
                    ], 200);
                }
            } else {
                $newTimeIn = new AttendanceIn;
                $newTimeIn->time = now();
                $newTimeIn->user_id = $user->id;
                $newTimeIn->save();
                $newAttendance = new Attendance;
                $newAttendance->time_in = $newTimeIn->time;
                $newAttendance->user_id = $user->id;
                $newAttendance->created_at = now();
                $newAttendance->updated_at = null;
                $newAttendance->save();
                return response()->json([
                    'card_allowed' => true,
                    'message' => 'Time in Recorded',
                ], 200);
            }
        } else {
            $card = BlankCard::where('card', $request->card)->first();
            if ($card) {
                return response()->json([
                    'card_allowed' => false,
                    'message' => 'Blank card',
                ], 200);
            } else {
                BlankCard::create([
                    "card" => $request->card
                ]);
                return response()->json([
                    'card_allowed' => false,
                    'message' => 'Card registered',
                ], 200);
            }
        }
    }
}
