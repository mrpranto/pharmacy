<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/home') }}">{{ __t('dashboard') }}</a></li>
        @foreach($paths as $key => $path)
            @if($key === (count($paths) - 1))
                <li class="breadcrumb-item active" aria-current="page">{{ __t($path) }}</li>
            @else
                <li class="breadcrumb-item"><a href="#">{{ __t($path) }}</a></li>
            @endif
        @endforeach
    </ol>
</nav>
