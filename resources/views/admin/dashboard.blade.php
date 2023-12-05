@extends('layouts.layout')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
    integrity="sha384-rJ8jnHi1OS+dsuZl2KT9tjs1+PR7FYrLs%2BEtD0cMgbb9LMpG0C5MKVR8WzDoOnfl" crossorigin="anonymous">

<x-admin-nav />
<div class="p-4 sm:ml-64">
    <div class="p-4  rounded-lg ">
        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">Users Attendance</h5>
        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400" id="printTable">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Employee</th>
                        @php
                        $today = today();
                        $dates = [];
                        for ($i = 1; $i < $today->daysInMonth + 1; ++$i) {
                            $dates[] = \Carbon\Carbon::createFromDate($today->year, $today->month,
                            $i)->format('Y-m-d');
                            }
                            @endphp
                            @foreach ($dates as $date)
                            <th style="" scope="col" class="px-6 py-3">
                                {{ $date }}
                            </th>
                            @endforeach
                    </tr>
                </thead>

                <tbody>
                    @foreach ($employees as $employee)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{
                            $employee->employee->name }}
                        </th>
                        @for ($i = 1; $i < $today->daysInMonth + 1; ++$i)
                            @php
                            $date_picker = \Carbon\Carbon::createFromDate($today->year, $today->month,
                            $i)->format('Y-m-d');

                            $check_attd = \App\Models\Attendance::query()
                            ->where('user_id', $employee->employee->id)
                            ->whereDate('time_in', $date_picker)
                            ->first();

                            $check_leave = \App\Models\Attendance::query()
                            ->where('user_id', $employee->employee->id)
                            ->whereDate('time_out', $date_picker)
                            ->first();
                            @endphp
                            <td class="px-6 py-4">
                                <div class="form-check form-check-inline ">
                                    @if (isset($check_attd))
                                    {{$date_picker =
                                    \Carbon\Carbon::createFromDate($check_attd->time_in)->format('H:i:s')}}
                                    @else
                                    <i class="fas fa-times text-red-400"></i>
                                    @endif
                                </div>
                                <div class="form-check form-check-inline">
                                    @if (isset($check_leave))
                                    {{\Carbon\Carbon::createFromDate($check_leave->time_out)->format('H:i:s')}}
                                    @else
                                    <i class="fas fa-times text-red-400"></i>
                                    @endif
                                </div>
                            </td>
                            @endfor
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop