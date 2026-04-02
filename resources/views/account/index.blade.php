<!DOCTYPE html>
<html>
<head>
    <title>Chart of Accounts</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .coa-item {
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: 0.2s;
        }

        .coa-item:hover {
            background: #f8f9fa;
        }

        .toggle {
            cursor: pointer;
            font-weight: bold;
            margin-right: 8px;
        }

        .children {
            margin-left: 25px;
            display: none;
        }

        .badge-type {
            font-size: 11px;
        }
    </style>
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow-lg border-0">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">📊 Chart of Accounts</h5>
            <a href="/accounts/create" class="btn btn-warning btn-sm">+ Tambah Akun</a>
        </div>

        <div class="card-body">

            @foreach($accounts as $acc)
                @include('account.partials.node', ['account' => $acc, 'level' => 0])
            @endforeach

        </div>
    </div>

</div>

<script>
    function toggle(el) {
        const targetId = el.getAttribute('data-target');
        const target = document.getElementById(targetId);

        if (!target) return;

        if (target.style.display === 'none' || target.style.display === '') {
            target.style.display = 'block';
            el.innerText = '▼';
        } else {
            target.style.display = 'none';
            el.innerText = '▶';
        }
    }
</script>
<style>
    .children {
        display: none;
        transition: all 0.3s ease;
    }
    
</style>
</body>
</html>