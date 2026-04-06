<div class="node d-flex justify-content-between"
     style="margin-left: {{ $level * 20 }}px;">

    <div>
        {{ $node['account']->code }} - {{ $node['account']->name }}
    </div>

    <div>
        {{ number_format($node['balance']) }}
    </div>
</div>

@foreach($node['children'] as $child)
    @include('report.partials.node', ['node'=>$child,'level'=>$level+1])
@endforeach