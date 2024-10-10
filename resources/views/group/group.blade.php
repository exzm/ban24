@extends('layouts.core')

@section('content')
    <section class="row category-page">
        <div class="w-100">
            <h1 class="px-3 h3 mb-1">{!! $group->icon !!} {{ $group->name }} {{ $city->in() }}</h1>
            <h2 class="d-block px-3 mb-2 text-muted small">
                <i class="fal fa-long-arrow-alt-right"></i> {{ \Illuminate\Support\Str::ucfirst($city->in()) }} находится {{ $count }} компан{{ ending($count, ['ия', 'ии', 'ий']) }}, которые занимаются {{ \Illuminate\Support\Str::lower($group->caseEd('tvor')) }}.
            </h2>
            <hr class="p-0 m-0 mb-2">
        </div>

        <div class="row w-100">
            <section class="col-md-3">
                <div class="stick mt-1">
                    @include('layouts.filters')
                    @include('comment.last')
                </div>
            </section>
            <section class="col-md-9">
                <div class="map">
                    <div id="map" data-url="{{ route('group-markers', [$city->url, $group->url]) }}">
                        <img class="map-img" src="{{ $img }}"
                             alt="{{ $group->name }} на карте {{ $city->caseEd('rod') }}"
                             title="Показать компании из категории «{{ $group->name }}» на карте {{ $city->caseEd('rod') }}">
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