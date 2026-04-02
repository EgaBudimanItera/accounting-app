<!DOCTYPE html>
<html>
<head>
    <title>Buku Besar</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4>Buku Besar</h4>
        </div>

        <div class="card-body">

            <!-- FORM PILIH AKUN -->
            <form method="GET" action="/report/ledger" class="mb-3">
                <div class="row">

                    <!-- ACCOUNT -->
                    <div class="col-md-4">
                        <label class="form-label">Account</label>
                        <select name="account_id" class="form-select">
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}"
                                    {{ $acc->id == $account->id ? 'selected' : '' }}>
                                    {{ $acc->code }} - {{ $acc->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- START DATE -->
                    <div class="col-md-3">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" name="start_date" class="form-control"
                            value="{{ $startDate }}">
                    </div>

                    <!-- END DATE -->
                    <div class="col-md-3">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" name="end_date" class="form-control"
                            value="{{ $endDate }}">
                    </div>

                    <!-- BUTTON -->
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-primary w-100">
                            Filter
                        </button>
                    </div>

                </div>
            </form>

            <h5>Account: {{ $account->name }}</h5>

            <!-- TABEL -->
            <div class="table-responsive mt-3">
                <div class="alert alert-warning">
                    <strong>Saldo Awal: {{ number_format($openingBalance,2) }}</strong>
                </div>
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        
                        <tr>
                            <th>Tanggal</th>
                            <th>Deskripsi</th>
                            <th class="text-end">Debit</th>
                            <th class="text-end">Credit</th>
                            <th class="text-end">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>

                        @php
                            $totalDebit = 0;
                            $totalCredit = 0;
                        @endphp

                        @foreach($details as $d)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($d->journal->date)->format('d-m-Y') }}</td>
                            <td>{{ $d->journal->description }}</td>
                            <td class="text-end">{{ number_format($d->debit,2) }}</td>
                            <td class="text-end">{{ number_format($d->credit,2) }}</td>
                            <td class="text-end fw-bold">
                                {{ number_format($d->balance,2) }}
                            </td>
                        </tr>

                        @php
                            $totalDebit += $d->debit;
                            $totalCredit += $d->credit;
                        @endphp

                        @endforeach
                        
                    </tbody>

                    <tfoot>
                        <tr class="table-success">
                            <th colspan="4">Saldo Akhir</th>
                            <th class="text-end">
                                {{ number_format($endingBalance,2) }}
                            </th>
                        </tr>
                    </tfoot>

                </table>
            </div>

        </div>
    </div>

</div>

</body>
</html>