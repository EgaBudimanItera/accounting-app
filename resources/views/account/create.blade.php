<!DOCTYPE html>
<html>
<head>
    <title>Tambah Akun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow">
        <div class="card-header">
            <h4>Tambah Akun</h4>
        </div>

        <div class="card-body">

            <form method="POST" action="/accounts/store">
                @csrf

                <div class="mb-3">
                    <label>Kode</label>
                    <input type="text" name="code" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Type</label>
                    <select name="type" class="form-control">
                        <option value="asset">Asset</option>
                        <option value="liability">Liability</option>
                        <option value="equity">Equity</option>
                        <option value="revenue">Revenue</option>
                        <option value="expense">Expense</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Parent</label>
                    <select name="parent_id" class="form-control">
                        <option value="">-- Root --</option>
                        @foreach($parents as $p)
                            <option value="{{ $p->id }}">
                                {{ $p->code }} - {{ $p->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Normal Balance</label>
                    <select name="normal_balance" class="form-control">
                        <option value="debit">Debit</option>
                        <option value="credit">Credit</option>
                    </select>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_receivable" value="1" class="form-check-input">
                    <label class="form-check-label">Piutang</label>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_payable" value="1" class="form-check-input">
                    <label class="form-check-label">Hutang</label>
                </div>

                <button class="btn btn-success">Simpan</button>
                <a href="/accounts" class="btn btn-secondary">Kembali</a>

            </form>

        </div>
    </div>

</div>

</body>
</html>