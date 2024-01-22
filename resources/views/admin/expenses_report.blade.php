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
    </style>
</head>

<body class="bg-opacity-50">
    <div class="container mx-auto p-4">
        <center>
            <h2 class="text-2xl font-semibold mb-4">List of all expenses</h2>
        </center>
        <table style="width: 100%" class="w-full table-auto border border-collapse">
            <thead>
                <tr>
                    <th style="padding: 8px; border: 1px solid #0c0c0c;">
                        Date
                    </th>
                    <th style="padding: 8px; border: 1px solid #0c0c0c;">
                        Name
                    </th>
                    <th style="padding: 8px; border: 1px solid #0c0c0c;">
                        Amount
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($expenses as $expense)
                <tr>
                    <td style="padding: 8px; border: 1px solid #0c0c0c;">
                        {{$expense->created_at}}
                    </td>
                    <td style="padding: 8px; border: 1px solid #0c0c0c;">
                        {{$expense->name}}
                    </td>
                    <td style="padding: 8px; border: 1px solid #0c0c0c;">
                        {{$expense->amount}} Rwf
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>