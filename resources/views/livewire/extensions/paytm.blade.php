<div>
    {{-- Success is as dangerous as failure. --}}
    @if($showView)
    
        <div>
            welcome bro {{ $showView ?'Show':'hide' }}
        </div>
    @endif
</div>
