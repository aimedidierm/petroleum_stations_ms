@extends('layouts.layout')

@section('content')
<x-employee-nav />
<div class="p-4 sm:ml-64">
    <div class="p-4  rounded-lg ">
        <div class="flew justify-between">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">Save payment</h5>
            @if($errors->any())<span
                class="self-center text-1xl text-red-600 font-semibold whitespace-nowrap dark:text-red-600">{{$errors->first()}}</span>
            @endif
        </div>
        <div
            class="p-6 space-y-6 relative bg-white border-2 border-green-600 dark:border-white rounded-lg shadow dark:bg-gray-700">
            <form class="space-y-4 md:space-y-6" method="POST" action="/employee/payments">
                @csrf
                <div>
                    <label for="amount" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Amount in
                        Rwf
                    </label>
                    <input type="text" name="amount" id="amount"
                        class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Enter amount" required>
                </div>
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Save</button>
            </form>
        </div>
    </div>
</div>
@stop