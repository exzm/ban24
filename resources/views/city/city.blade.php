@extends('layouts.core')

@section('content')
    <section class="row">
        <div class="p-1">
            <h1 class="h2 ml-2"><i class="fal fa-car-garage"></i> Автопортал {{ $city->in() }}</h1>
            <h2 class="small text-muted ml-sm-3">
                <i class="fal fa-long-arrow-alt-right"></i> Всего найдено {{ $count }} компан{{ ending($count, ['ия', 'ии', 'ий']) }}
            </h2>
        </div>
        <section class="row w-100">
            <section class="col-md-3">
                @include('layouts.left-sidebar', ['city' => $city, 'groups' => $groups])
            </section>
            <section class="col-md-9">
                @include('firm.firm_list', ['firms' => $firms])
            </section>
        </section>
    </section>
@endsection