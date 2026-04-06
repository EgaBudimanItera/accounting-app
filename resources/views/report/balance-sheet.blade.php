<!DOCTYPE html>
<html>
<head>
    <title>Neraca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .section-title {
            font-weight: bold;
            font-size: 18px;
            margin-top: 20px;
        }

        .node {
            padding: 6px 0;
        }

        .line {
            border-bottom: 2px solid #000;
            margin: 10px 0;
        }

        .total {
            font-weight: bold;
            border-top: 2px solid #000;
            padding-top: 5px;
        }
    </style>
</head>

<body class="bg-light">

<div class="container mt-5">

<div class="card shadow-lg border-0">

    <div class="card-header bg-dark text-white">
        📊 Neraca (Balance Sheet)
    </div>

    <div class="card-body">

        <!-- FILTER -->
        <form class="row mb-4">
            <div class="col-md-4">
                <input type="date" name="start_date" class="form-control"
                       value="{{ request('start_date') }}">
            </div>

            <div class="col-md-4">
                <input type="date" name="end_date" class="form-control"
                       value="{{ request('end_date') }}">
            </div>

            <div class="col-md-4">
                <button class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        <!-- ================= ASSET ================= -->
        <div class="section-title text-success">ASSET</div>

        @php $totalAsset = 0; @endphp

        @foreach($data['assets'] as $node)
            @include('report.partials.node', ['node'=>$node,'level'=>0])
            @php $totalAsset += $node['balance']; @endphp
        @endforeach

        <div class="line"></div>

        <div class="d-flex justify-content-between total">
            <div>Total Asset</div>
            <div>{{ number_format($totalAsset) }}</div>
        </div>

        <!-- ================= LIABILITY ================= -->
        <div class="section-title text-danger">LIABILITY</div>

        @php $totalLiability = 0; @endphp

        @foreach($data['liabilities'] as $node)
            @include('report.partials.node', ['node'=>$node,'level'=>0])
            @php $totalLiability += $node['balance']; @endphp
        @endforeach

        <div class="line"></div>

        <div class="d-flex justify-content-between total">
            <div>Total Liability</div>
            <div>{{ number_format($totalLiability) }}</div>
        </div>

        <!-- ================= EQUITY ================= -->
        <div class="section-title text-primary">EQUITY</div>

        @php $totalEquity = 0; @endphp

        @foreach($data['equities'] as $node)
            @include('report.partials.node', ['node'=>$node,'level'=>0])
            @php $totalEquity += $node['balance']; @endphp
        @endforeach

        <div class="line"></div>

        <div class="d-flex justify-content-between total">
            <div>Total Equity</div>
            <div>{{ number_format($totalEquity) }}</div>
        </div>

        <!-- ================= GRAND TOTAL ================= -->
        <div class="line"></div>

        <div class="d-flex justify-content-between total">
            <div>Total Liability + Equity</div>
            <div>{{ number_format($totalLiability + $totalEquity) }}</div>
        </div>

        <!-- ================= STATUS ================= -->
        <div class="mt-3">
            @if($totalAsset == ($totalLiability + $totalEquity))
                <div class="alert alert-success">
                    ✔ Neraca Balance
                </div>
            @else
                <div class="alert alert-danger">
                    ❌ Neraca Tidak Balance
                </div>
            @endif
        </div>

    </div>

</div>

</div>

</body>
</html>