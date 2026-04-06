<!DOCTYPE html>
<html>
<head>
    <title>Trial Balance</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .card-header {
            font-weight: 600;
        }

        .total-row {
            background: #f8f9fa;
            font-weight: bold;
        }
    </style>
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow-lg border-0">

        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <span>📊 Trial Balance</span>
        </div>

        <div class="card-body">

            <!-- FILTER -->
            <form method="GET" class="row g-3 mb-4">

                <div class="col-md-4">
                    <label>Start Date</label>
                    <input type="date" name="start_date" class="form-control"
                        value="{{ request('start_date') }}">
                </div>

                <div class="col-md-4">
                    <label>End Date</label>
                    <input type="date" name="end_date" class="form-control"
                        value="{{ request('end_date') }}">
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-primary w-100">
                        Filter
                    </button>
                </div>

            </form>

            <!-- TABLE -->
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">

                    <thead class="table-dark text-center">
                        <tr>
                            <th class="text-start">Akun</th>
                            <th width="200">Debit</th>
                            <th width="200">Credit</th>
                        </tr>
                    </thead>

                    <tbody>

                        @php
                            $totalDebit = 0;
                            $totalCredit = 0;
                        @endphp

                        @foreach($data as $row)

                            @php
                                $totalDebit += $row['debit'];
                                $totalCredit += $row['credit'];
                            @endphp

                            <tr>
                                <td>
                                    <strong>{{ $row['account']->code }}</strong> 
                                    - {{ $row['account']->name }}
                                </td>

                                <td class="text-end text-success">
                                    {{ number_format($row['debit']) }}
                                </td>

                                <td class="text-end text-danger">
                                    {{ number_format($row['credit']) }}
                                </td>
                            </tr>

                        @endforeach

                    </tbody>

                    <!-- TOTAL -->
                    <tfoot>
                        <tr class="total-row">
                            <td class="text-end">TOTAL</td>
                            <td class="text-end text-success">
                                {{ number_format($totalDebit) }}
                            </td>
                            <td class="text-end text-danger">
                                {{ number_format($totalCredit) }}
                            </td>
                        </tr>
                    </tfoot>

                </table>
            </div>

            <!-- VALIDATION -->
            <div class="mt-3 text-end">
                @if($totalDebit == $totalCredit)
                    <span class="badge bg-success">
                        Balance ✔ (Debit = Credit)
                    </span>
                @else
                    <span class="badge bg-danger">
                        Tidak Balance ❌
                    </span>
                @endif
            </div>

        </div>
    </div>

</div>

</body>
</html>