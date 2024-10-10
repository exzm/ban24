@extends('layouts.core')

@section('content')
    <section class="row category-page">
        <div class="w-100">
            <h1 class="px-3 h3 mb-1">{!! $key->icon !!} {{ $key->name }} {{ $city->in() }}</h1>
            <small class="d-block px-3 mb-2 text-muted">
                {{ \Illuminate\Support\Str::ucfirst($city->in()) }} находится {{ $count }} компан{{ ending($count, ['ия', 'ии', 'ий']) }} из категории «{{ $key->name }}».
            </small>
            <hr class="p-0 m-0 mb-2">
        </div>

        <div class="row w-100">
            <section class="col-sm-3">
                <div class="stick mt-1">
                    <div data-url="{{ route('keyword-filter', [$city->url, $key->url]) }}" id="filters" class="ml-2">
                        <button title="Найти работающие компании" data-type="open" type="button" class="btn btn-light btn-sm btn-block font-weight-bold">
                            <i class="fal fa-clock"></i> Сейчас открыто
                        </button>
                        <button title="Найти компании рядом с вами" data-type="near" type="button" class="btn btn-light btn-sm btn-block font-weight-bold">
                            <i class="fal fa-map-marker-alt"></i> Рядом
                        </button>
                    </div>
                    @if ($key->options)
                        <hr>
                        <div class="mt-2">
                            <h2 class="ml-3 font-size-90 font-weight-bold">
                                <i class="fal fa-info-square"></i> {{ $key->options['name'] }}
                            </h2>
                            <ul class="small">
                                @foreach($key->options['list'] AS $option)
                                    @break($loop->index == 5)
                                    <li>{{ $option }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @include('comment.last')
                </div>
            </section>
            <section class="col-sm-9">
                <div class="map">
                    <div id="map" data-url="{{ route('keyword-markers', [$city->url, $key->url]) }}">
                        <img class="map-img" src="{{ $img }}"
                             alt="{{ $key->name }} на карте {{ $city->caseEd('rod') }}"
                             title="Показать компании из категории «{{ $key->name }}» на карте {{ $city->caseEd('rod') }}">
                    </div>
                    <div class="show-map">
                        <i class="fas fa-arrow-to-bottom"></i>
                    </div>
                </div>

                @include('firm.firm_list', ['firms' => $firms])
            </section>
        </div>
    </section>
    <script>
        var City = {!! json_encode(['lat' => $city->lat, 'lon' => $city->lon]) !!};
    </script>
@endsection