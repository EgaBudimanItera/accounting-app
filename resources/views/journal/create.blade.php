<!DOCTYPE html>
<html>
<head>
    <title>Input Jurnal</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4>Input Jurnal</h4>
        </div>

        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <form method="POST" action="/journal/store">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="date" class="form-control">
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Deskripsi</label>
                        <input type="text" name="description" class="form-control">
                    </div>
                </div>

                <h5>Detail Jurnal</h5>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Account</th>
                                <th width="150">Debit</th>
                                <th width="150">Credit</th>
                                <th width="100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="journal-body">
                            <tr>
                                <td>
                                    <select name="details[0][account_id]" class="form-select">
                                        @foreach($accounts as $acc)
                                            <option value="{{ $acc->id }}">
                                                {{ $acc->code }} - {{ $acc->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="details[0][debit]" class="form-control debit" value="0">
                                </td>
                                <td>
                                    <input type="number" name="details[0][credit]" class="form-control credit" value="0">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">X</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <button type="button" class="btn btn-secondary mb-3" onclick="addRow()">+ Tambah Baris</button>

                <div class="alert alert-info">
                    <strong id="balance">Debit: 0 | Credit: 0</strong>
                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan Jurnal
                </button>
            </form>

        </div>
    </div>
</div>

<script>
let index = 1;

function addRow() {
    let table = document.getElementById('journal-body');

    let row = `
        <tr>
            <td>
                <select name="details[${index}][account_id]" class="form-select">
                    @foreach($accounts as $acc)
                        <option value="{{ $acc->id }}">
                            {{ $acc->code }} - {{ $acc->name }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" name="details[${index}][debit]" class="form-control debit" value="0">
            </td>
            <td>
                <input type="number" name="details[${index}][credit]" class="form-control credit" value="0">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">X</button>
            </td>
        </tr>
    `;

    table.insertAdjacentHTML('beforeend', row);
    index++;
}

function removeRow(button) {
    button.closest('tr').remove();
    calculateBalance();
}

function calculateBalance() {
    let debits = document.querySelectorAll('.debit');
    let credits = document.querySelectorAll('.credit');

    let totalDebit = 0;
    let totalCredit = 0;

    debits.forEach(d => totalDebit += parseFloat(d.value) || 0);
    credits.forEach(c => totalCredit += parseFloat(c.value) || 0);

    document.getElementById('balance').innerText =
        "Debit: " + totalDebit + " | Credit: " + totalCredit;
}

document.addEventListener('input', calculateBalance);

</script>

</body>
</html>