@extends('layouts.layout')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
    integrity="sha384-rJ8jnHi1OS+dsuZl2KT9tjs1+PR7FYrLs%2BEtD0cMgbb9LMpG0C5MKVR8WzDoOnfl" crossorigin="anonymous">

<x-admin-nav />
<div class="p-4 sm:ml-64">
    <div class="p-4  rounded-lg ">
        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">Users Attendance</h5>
        <div class="justify-between flex">
            <h1></h1>
            <button data-modal-target="printReport" data-modal-toggle="printReport"
                class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Generate report
            </button>
        </div>
        <br>
        <div id="printReport" data-modal-backdrop="static" tabindex="-1" aria-hidden="true"
            class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-2xl max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Select report details
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-hide="printReport">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="p-6 space-y-6">

                        <form action="/admin/report/attendance" method="post">
                            @csrf

                            <div class="mb-6">
                                <label for="reportDate"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Report Month
                                    :</label>
                                <input type="date" id="reportDate" name="reportDate"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            </div>
                            <button type="submit"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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