<div class="row row-striped mb-1 border">
    <div class="col-sm-7">
        <div class="mt-2 mb-0 clearfix">
            @if ($firm->isOpen($firm->city->timezone))
                <i title="Сейчас открыто" class="fas fa-lock-open-alt text-success"></i>
            @else
                <i title="Сейчас закрыто" class="fas fa-lock-alt text-danger"></i>
            @endif
            <a title="{{ $firm->name }} - {{ $firm->address }} {{ $firm->city->in() }}, телефон, сайт, цены и отзывы" href="{{ route('firm', [$firm->city->url, $firm->url]) }}">
                <strong>{{ $firm->name }}</strong>
            </a>
            <div class="float-right small text-muted" title="{{ $firm->rating['count'] }} голос{{ ending($firm->rating['count'], ['', 'а', 'ов']) }}">
                @include('layouts.stars', ['score' => $firm->rating['avg']])
            </div>
            <br>
        </div>
        <small class="small text-muted h5">{{ $firm->subtitle }}</small>
        <ul class="list-inline small mt-1 text-muted">
            <li><i class="fal fa-map-marked"></i> {{ $firm->address }}</li>
            @if ($firm->phones->count() > 0)
                <li><i class="fal fa-phone-volume"></i> {{ $firm->phones->first()->text }}</li>
            @endif
            @if ($firm->distance)
                <li><i class="fal fa-location-circle"></i> {{ round($firm->distance, 2) }} м.</li>
            @endif
        </ul>
    </div>
    <div class="col-sm-5 p-1">
        <img src="{{ $firm->getImg('345,140') }}"
             alt="{{ $firm->name }} на карте {{ $firm->city->caseEd('rod') }}"
             title="{{ $firm->name }}, {{ $firm->address }} на карте {{ $firm->city->caseEd('rod') }}" class="text-right w-100">
    </div>
</div>