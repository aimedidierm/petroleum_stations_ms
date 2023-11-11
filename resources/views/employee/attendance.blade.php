@extends('layouts.layout')

@section('content')
<x-employee-nav />
<div class="p-4 sm:ml-64">
    <div class="p-4  rounded-lg ">
        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">Your attendance</h5>


        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Date
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Time In
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Time out
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attendances as $attendance)
                    <tr class="bg-white dark:bg-gray-800">
                        <th style="" scope="col" class="px-6 py-3 dark:text-white">
                            {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $attendance->time_in)->format('Y-m-d') }}
                        </th>

                        <td class="px-6 py-4">
                            @if (isset($attendance->time_in))
                            {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $attendance->time_in)->format('H:i') }}
                            @else
                            <span class="text-red-600">Absent</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if (isset($attendance->time_out))
                            {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $attendance->time_out)->format('H:i') }}
                            @else
                            <span class="text-red-600">Absent</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>
@stop