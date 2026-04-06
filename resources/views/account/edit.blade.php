<!DOCTYPE html>
<html>
<head>
    <title>Edit Akun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<!-- Tambahkan ini untuk debug -->
<pre>
{{ print_r($account->toArray(), true) }}
</pre>

<!-- Atau cek apakah ada data -->
@if($account)
    <p>Account ID: {{ $account->id }}</p>
    <p>Account Code: {{ $account->code }}</p>
    <p>Account Name: {{ $account->name }}</p>
@else
    <p>Data tidak ditemukan!</p>
@endif
<div class="container mt-5">

    <div class="card shadow">
        <div class="card-header">
            <h4>Edit Akun</h4>
        </div>

        <div class="card-body">

            <form method="POST" action="/accounts/{{ $account->id }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>Kode</label>
                    <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" 
                           value="{{ $account->code }}" required>
                    @error('code')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ $account->name }}" required>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Type</label>
                    <select name="type" class="form-control @error('type') is-invalid @enderror">
                        <option value="">-- Pilih Type --</option>
                        <option value="asset" {{ $account->type === 'asset' ? 'selected' : '' }}>Asset</option>
                        <option value="liability" {{ $account->type === 'liability' ? 'selected' : '' }}>Liability</option>
                        <option value="equity" {{ $account->type === 'equity' ? 'selected' : '' }}>Equity</option>
                        <option value="revenue" {{ $account->type === 'revenue' ? 'selected' : '' }}>Revenue</option>
                        <option value="expense" {{ $account->type === 'expense' ? 'selected' : '' }}>Expense</option>
                    </select>
                    @error('type')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Parent</label>
                    <select name="parent_id" class="form-control @error('parent_id') is-invalid @enderror">
                        <option value="">-- Root --</option>
                        @foreach($parents as $p)
                            <option value="{{ $p->id }}" {{ $account->parent_id == $p->id ? 'selected' : '' }}>
                                {{ $p->code }} - {{ $p->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Normal Balance</label>
                    <select name="normal_balance" class="form-control @error('normal_balance') is-invalid @enderror">
                        <option value="">-- Pilih Balance --</option>
                        <option value="debit" {{ $account->normal_balance === 'debit' ? 'selected' : '' }}>Debit</option>
                        <option value="credit" {{ $account->normal_balance === 'credit' ? 'selected' : '' }}>Credit</option>
                    </select>
                    @error('normal_balance')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_receivable" value="1" class="form-check-input" 
                           {{ $account->is_receivable ? 'checked' : '' }}>
                    <label class="form-check-label">Piutang</label>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_payable" value="1" class="form-check-input" 
                           {{ $account->is_payable ? 'checked' : '' }}>
                    <label class="form-check-label">Hutang</label>
                </div>

                <button class="btn btn-success">Update</button>
                <a href="/accounts" class="btn btn-secondary">Kembali</a>

            </form>

        </div>
    </div>

</div>

</body>
</html>