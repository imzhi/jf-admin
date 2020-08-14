<div class="clearfix">
    <nav class="pull-left m-r-xs" style="line-height: 28px;">
        @if($paginator->total())
        <small>从 {{ ($paginator->currentPage() - 1) * $paginator->perPage() + 1 }} 到 {{ $paginator->currentPage() * $paginator->perPage() < $paginator->total() ? $paginator->currentPage() * $paginator->perPage() : $paginator->total() }}，共 {{ $paginator->total() }} 条</small>
        @else
        <small>共 {{ $paginator->total() }} 条</small>
        @endif
    </nav>
    <nav class="pull-right">
        <ul class="pagination m-b-none">
            @if($paginator->currentPage() > 1)
            <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}"><span>&laquo;</span></a></li>
            @else
            <li class="page-item disabled"><a class="page-link" href="#"><span>&laquo;</span></a></li>
            @endif
            @if($paginator->currentPage() - 3 >= 1)
            <li class="page-item"><a class="page-link" href="{{ $paginator->url(1) }}">1</a></li>
            @endif
            @if($paginator->currentPage() - 4 >= 1)
            <li class="page-item disabled"><a class="page-link" href="#"><span>...</span></a></li>
            @endif
            @for($p = max(1, $paginator->currentPage() - 2); $p <= max(0, $paginator->currentPage() - 1); $p++)
            <li class="page-item"><a class="page-link" href="{{ $paginator->url($p) }}">{{ $p }}</a></li>
            @endfor
            <li class="page-item active"><a class="page-link" href="javascript:">{{ $paginator->currentPage() }}</a></li>
            @for($p = $paginator->currentPage() + 1; $p <= min($paginator->lastPage(), $paginator->currentPage() + 2); $p++)
            <li class="page-item"><a class="page-link" href="{{ $paginator->url($p) }}">{{ $p }}</a></li>
            @endfor
            @if($paginator->currentPage() + 4 <= $paginator->lastPage())
            <li class="page-item disabled"><a class="page-link" href="#"><span>...</span></a></li>
            @endif
            @if($paginator->currentPage() + 3 <= $paginator->lastPage())
            <li class="page-item"><a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a></li>
            @endif
            @if($list->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}">&raquo;</a></li>
            @else
            <li class="page-item disabled"><a class="page-link" href="#"><span>&raquo;</span></a></li>
            @endif
        </ul>
    </nav>
    {{-- <div class="m-r pull-right" style="line-height: 28px;">
        显示
        <select id="jf_per_page">
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
        条
    </div> --}}
</div>
