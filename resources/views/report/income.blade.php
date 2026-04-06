<!DOCTYPE html>
<html>
<head>
    <title>Laba Rugi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow-lg border-0">

        <div class="card-header bg-dark text-white">
            💰 Laporan Laba Rugi
        </div>

        <div class="card-body">

            <!-- FILTER -->
            <form class="row mb-4">
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
                    <button class="btn btn-primary w-100">Filter</button>
                </div>
            </form>

            <!-- TABLE -->
            <table class="table table-bordered">

                <tr class="table-success">
                    <th>Total Pendapatan</th>
                    <td class="text-end">{{ number_format($data['revenue']) }}</td>
                </tr>

                <tr class="table-danger">
                    <th>Total Beban</th>
                    <td class="text-end">{{ number_format($data['expense']) }}</td>
                </tr>

                <tr class="table-dark text-white">
                    <th>Laba Bersih</th>
                    <td class="text-end">{{ number_format($data['profit']) }}</td>
                </tr>

            </table>

        </div>
    </div>

</div>

</body>
</html>