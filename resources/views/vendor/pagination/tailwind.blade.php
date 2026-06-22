@if ($paginator->hasPages())
<div style="display:flex;flex-direction:column;align-items:center;padding:0.75rem 0;">
    <div style="display:flex;gap:5px;flex-wrap:wrap;justify-content:center;align-items:center;">
        @if ($paginator->onFirstPage())
            <span style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:50%;font-size:0.7rem;font-weight:700;color:#D4D4D8;background:#FAFAF9;border:2px solid #F5F5F4;cursor:default;">‹</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:50%;font-size:0.7rem;font-weight:700;color:#78716C;background:white;border:2px solid #FED7AA;text-decoration:none;transition:all 0.2s;" onmouseover="this.style.background='#FFF7ED';this.style.color='#E85D3A';this.style.borderColor='#E85D3A'" onmouseout="this.style.background='white';this.style.color='#78716C';this.style.borderColor='#FED7AA'">‹</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span style="display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;font-size:0.7rem;font-weight:700;color:#A8A29E;">…</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:50%;font-size:0.75rem;font-weight:700;color:white;background:linear-gradient(135deg,#E85D3A,#DC2626);border:none;box-shadow:0 2px 8px rgba(220,38,38,0.3);">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:50%;font-size:0.75rem;font-weight:700;color:#78716C;background:white;border:2px solid #FED7AA;text-decoration:none;transition:all 0.2s;" onmouseover="this.style.background='#FFF7ED';this.style.color='#E85D3A';this.style.borderColor='#E85D3A'" onmouseout="this.style.background='white';this.style.color='#78716C';this.style.borderColor='#FED7AA'">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:50%;font-size:0.7rem;font-weight:700;color:#78716C;background:white;border:2px solid #FED7AA;text-decoration:none;transition:all 0.2s;" onmouseover="this.style.background='#FFF7ED';this.style.color='#E85D3A';this.style.borderColor='#E85D3A'" onmouseout="this.style.background='white';this.style.color='#78716C';this.style.borderColor='#FED7AA'">›</a>
        @else
            <span style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:50%;font-size:0.7rem;font-weight:700;color:#D4D4D8;background:#FAFAF9;border:2px solid #F5F5F4;cursor:default;">›</span>
        @endif
    </div>
    <div style="font-size:0.65rem;color:#A8A29E;margin-top:0.4rem;font-weight:600;">
        Mostrando {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} de {{ $paginator->total() }}
    </div>
</div>
@endif
