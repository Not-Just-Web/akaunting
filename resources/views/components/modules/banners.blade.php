@if (! empty($modules) && ! empty($modules[0]))
    @foreach ($modules as $item)
        {!! $item !!}
    @endforeach
@endif
