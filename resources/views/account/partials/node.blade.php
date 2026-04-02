@php
    $nodeId = 'node-' . $account->id;
@endphp

<div class="coa-item d-flex justify-content-between align-items-center"
     style="margin-left: {{ $level * 20 }}px;">

    <div>
        @if($account->children->count())
            <span class="toggle" data-target="{{ $nodeId }}" onclick="toggle(this)">
                ▶
            </span>
        @else
            <span class="text-muted">•</span>
        @endif

        <strong>{{ $account->code }}</strong> - {{ $account->name }}
    </div>

    <div>
        <span class="badge bg-secondary">{{ $account->type }}</span>

        @if($account->normal_balance == 'debit')
            <span class="badge bg-success">Debit</span>
        @else
            <span class="badge bg-danger">Credit</span>
        @endif
    </div>
    <div class="d-flex gap-2">

        {{-- EDIT --}}
        @if(!$account->isUsed())
            <a href="/accounts/{{ $account->id }}/edit"
            class="btn btn-sm btn-outline-primary">
                Edit
            </a>
        @else
            <button class="btn btn-sm btn-secondary" disabled>
                Locked
            </button>
        @endif


        {{-- DELETE --}}
        @if(!$account->isUsed() && !$account->hasChildren())
            <form action="/accounts/{{ $account->id }}" method="POST"
                onsubmit="return confirm('Yakin hapus akun ini?')">
                @csrf
                @method('DELETE')

                <button class="btn btn-sm btn-outline-danger">
                    Hapus
                </button>
            </form>
        @endif

    </div>
</div>

@if($account->children->count())
    <div id="{{ $nodeId }}" class="children">
        @foreach($account->children as $child)
            @include('account.partials.node', [
                'account' => $child,
                'level' => $level + 1
            ])
        @endforeach
    </div>
@endif