{{-- @typography and @card are components from the Municipio component library. --}}

@if (!$hideTitle && !empty($postTitle))
    @typography(['element' => 'h2', 'variant' => 'h4', 'classList' => ['module-title']])
        {!! $postTitle !!}
    @endtypography
@endif

@if ($hasBackground)
    @card([])
        <div class="c-card__body">
            {!! $content !!}
        </div>
    @endcard
@else
    {!! $content !!}
@endif
