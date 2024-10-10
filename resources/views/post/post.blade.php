@extends('layouts.core')

@section('content')
    <h1 class="h3"><i class="fal fa-newspaper"></i> {{ $wall->name }}</h1>
    @if ($posts->currentPage() > 1)
        <h2 class="small text-muted">Страница {{ $posts->currentPage() }}</h2>
    @endif
    <h3 class="small">
        <a title="Перейти к компании {{ $firm->name }}" href="{{ route('firm', [$city->url, $firm->url]) }}">
            <i class="fal fa-hand-point-right"></i> {{ $firm->name }} {{ $city->in() }}
        </a>
    </h3>
    <hr class="p-1">

    <section>
        @foreach($posts AS $post)
            <div class="clearfix">
                <h3 class="text-info h5"><i class="fal fa-clock"></i> {{ $post->date }}</h3>
                @if ($post->bigPreview)
                    <div class="float-left pr-2">
                        <img src="{{ $post->bigPreview->path }}" alt="">
                    </div>
                @endif
                <div class="small">
                    {!! $post->text !!}
                </div>
                @if ($post->photos->count() > 1)
                    <div data-featherlight-gallery data-featherlight-filter="a" class="gallery">
                        @foreach($post->photos AS $photo)
                            @continue($loop->index == 0)
                            <a title="Фото {{ $loop->iteration }}" href="{{ $photo->last()->path }}">
                                <img title="Фото {{ $loop->iteration }}" alt="Фото {{ $loop->iteration }}"class="p-1" src="{{ $photo->first()->path }}">
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
            <hr class="p-1">
        @endforeach
    </section>
    <div>
        {{ $posts->links() }}
    </div>
@endsection