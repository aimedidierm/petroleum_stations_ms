<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Expense report</title>
    <style>
        .container {
            margin-left: auto;
            margin-right: auto;
            padding: 16px;
        }

        .text-2xl {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #e2e8f0;
        }

        .table th,
        .table td {
            padding: 8px;
            border: 1px solid #e2e8f0;
        }

        .table-data {
            font-size: 8px;
        }
    </style>
</head>

<body class="bg-opacity-50">
    <div class="container mx-auto p-4">
        <center>
            <h2 class="text-2xl font-semibold mb-4">Attendance report</h2>
        </center>
        <table style="width: 100%" class="w-full table-auto border border-collapse">
            <thead>
                <tr>
                    <th class="table-data" scope="col" style="padding: 8px; border: 1px solid #0c0c0c;">
                        Employee</th>
                    @php
                    $today = today();
                    $dates = [];
                    for ($i = 1; $i < $today->daysInMonth + 1; ++$i) {
                        $dates[] = \Carbon\Carbon::createFromDate($today->year, $today->month,
                        $i)->format('Y-m-d');
                        }
                        @endphp
                        @foreach ($dates as $date)
                        <th class="table-data" style="padding: 8px; border: 1px solid #0c0c0c;" scope="col">
                            {{ $date }}
                        </th>
                        @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                <tr>
                    <th class="table-data" scope="row" style="padding: 8px; border: 1px solid #0c0c0c;">{{
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
                        <td class="table-data" style="padding: 8px; border: 1px solid #0c0c0c;">
                            <div class="form-check form-check-inline ">
                                @if (isset($check_attd))
                                {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $check_attd->time_in)->format('H:i')
                                }}
                                @else
                                <span class="text-red-600">Absent</span>
                                @endif
                            </div>
                            <div class="form-check form-check-inline">

                                @if (isset($check_attd->time_out))
                                {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $check_attd->time_out)->format('H:i')
                                }}
                                @else
                                <span class="text-red-600">Absent</span>
                                @endif
                            </div>
                        </td>
                        @endfor
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>