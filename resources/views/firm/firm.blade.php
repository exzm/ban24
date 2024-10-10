@extends('layouts.core')

@section('content')
    <section role="main" itemscope="" itemtype="http://schema.org/Organization" class="firm-page">
        <div>
            @if ($wall)
                <img src="{{ $wall->preview }}" alt="Логотип {{ $firm->name }}" title="Логотип {{ $firm->name }}" class="float-right">
            @endif
            <div>
                <h1 class="h3 mb-0 font-weight-bold" itemprop="name">
                    {{ $firm->name }}
                </h1>
                <h2 class="h5">
                    <small class="text-muted">{!! $firm->groups->first()->icon !!} {{ $firm->subtitle }}</small>
                </h2>
            </div>

        </div>

        <div class="row text-center firm-menu small">
            <div class="col-sm-2 border">
                <a title="Телефоны компании" href="#phones"><i class="fal fa-phone-office"></i> Tелефон</a>
            </div>
            <div class="col-sm-2 border">
                <a title="Фотографии компании" href="#photos"><i class="fal fa-images"></i> Фото</a>
            </div>
            <div class="col-sm-2 border">
                @if($firm->isOpen($city->timezone))
                    <a title="{{ $firm->name }} сейчас работает" href="#worktime" class="text-success"><i class="fal fa-clock"></i> Сейчас открыто</a>
                @else
                    <a title="{{ $firm->name }} сейчас не работает" href="#worktime" class="text-danger"><i class="fal fa-clock"></i> Сейчас закрыто</a>
                @endif
            </div>
            <div class="col-sm-2 border">
                <a title="Отзывы о {{ $firm->name }}" class="quick-menu-link" href="#reviews" id="link-menu-reviews">
                    <i class="fal fa-comment-lines"></i>
                    @if ($reviews->count() > 0)
                        {{ $reviews->count() }} отзыв{{ ending($reviews->count(), ['', 'а', 'ов']) }}
                    @else
                        Отзывы
                    @endif
                </a>
            </div>
            <div class="col-sm-2 border">
                <a href="#route" data-url="{{ route('modal-route', [$firm->id]) }}" class="service-button"
                   title="Схема проезда до {{ $firm->subtitleCases ? $firm->subtitleCases->caseEd(RU_RO) : 'компании' }}">
                    <i class="fal fa-route"></i> Как доехать?
                </a>
            </div>
            <div class="col-sm-2 border">
                <a href="#near" title="Похожие места рядом c {{ $firm->subtitleCases ? $firm->subtitleCases->caseEd(RU_TV) : 'компанией' }}"><i class="fal fa-clone"></i> Похожие
                    места</a>
            </div>
        </div>

        <div class="row px-md-3 py-1">
            <div class="col-md-7 passport">
                @include('ads.yandex.firm-1')
                <div class="mt-2">
                    <div class="d-inline-block w-25 name">
                        <h3><i class="fal fa-star-exclamation"></i> Оценка:</h3>
                    </div>
                    <div class="d-inline-block w-70">
                        <div itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
                            <meta itemprop="ratingValue" content="{{ $firm->rating['avg'] }}">
                            <meta itemprop="ratingCount" content="{{ $firm->rating['count'] }}">
                            <meta itemprop="worstRating" content="1">
                            <meta itemprop="bestRating" content="5">
                            <div class="d-inline-block mr-2">
                                <select id="rating" data-url="{{ route('firm-rating-post', [$firm->id]) }}">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                            <div class="d-inline-block">
                                <small class="rating-count text-muted">
                                    {{ $firm->rating['count'] }} голос{{ ending($firm->rating['count'], ['', 'а', 'ов']) }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                @if($firm->phones->count() > 0)
                    <hr class="m-1">
                    <div id="phones">
                        <div class="d-inline-block w-25 name align-top">
                            <h3><i class="fal fa-phone-volume"></i> Телефон{{ $firm->phones->count() == 1 ? '' : 'ы' }}:</h3>
                        </div>
                        <div class="d-inline-block w-70">
                            @foreach($firm->phones AS $phone)
                                <div>
                                    <a href="tel:{{ $phone->value }}" itemprop="telephone" title="{{ $phone->comment ?: "Позвонить в {$firm->name}" }}">
                                        {{ $phone->text }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($firm->faxes->count() > 0)
                    <hr class="m-1">
                    <div>
                        <div class="d-inline-block w-25 name align-top">
                            <h3 class="hp"><i class="fal fa-fax"></i> Факс:</h3>
                        </div>
                        <div class="d-inline-block w-70">
                            @foreach($firm->faxes AS $fax)
                                <div>
                                    <span itemprop="faxNumber">{{ $fax->text }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($firm->emails->count() > 0)
                    <hr class="m-1">
                    <div>
                        <div class="d-inline-block w-25 name align-top">
                            <h3><i class="fal fa-at"></i> Email:</h3>
                        </div>
                        <div class="d-inline-block w-70">
                            @foreach($firm->emails AS $email)
                                <div>
                                    <a title="E-mail {{ $firm->name }}" rel="nofollow" target="_blank" href="mailto:{{ $email->value }}" itemprop="email">{{ $email->text }}</a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($firm->official_name)
                    <hr class="m-1">
                    <div>
                        <div class="d-inline-block w-25 name">
                            <h3><i class="fal fa-balance-scale"></i> Юр. лицо:</h3>
                        </div>
                        <div class="d-inline-block w-70">
                            <div itemprop="legalName">{{ $firm->official_name }}</div>
                        </div>
                    </div>
                @endif

                @if ($firm->sites->count() > 0)
                    <hr class="m-1">
                    <div>
                        <div class="d-inline-block w-25 name align-top">
                            <h3><i class="fal fa-browser"></i> Сайт{{ $firm->sites->count() == 1 ? '' : 'ы' }}:</h3>
                        </div>
                        <div class="d-inline-block w-70">
                            @foreach($firm->sites AS $site)
                                <div>
                                    <a title="{{ $site->comment ?: "Официальный сайт {$firm->name}" }}" rel="nofollow" target="_blank" href="{{ $site->url }}">
                                        {{ $site->text }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($firm->social->count() > 0)
                    <hr class="m-1">
                    <div>
                        <div class="d-inline-block w-25 name align-top">
                            <h3><i class="fal fa-chart-network"></i> Соц. сети:</h3>
                        </div>
                        <div class="d-inline-block w-70">
                            @foreach($firm->social AS $social)
                                <a class="mr-1" title="{{ $social->comment ?: "{$social->type} {$firm->name}" }}" rel="nofollow" target="_blank" href="{{ $social->url }}">
                                    {!! $social->icon !!}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($firm->address)
                    <hr class="m-1">
                    <div>
                        <div class="d-inline-block w-25 name align-top">
                            <h3><i class="fal fa-map-marked-alt"></i> Адрес: </h3>
                        </div>
                        <div class="d-inline-block w-70">
                            <div itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress">
                                <meta itemprop="addressCountry" content="{{ $city->region->country->name }}">
                                <meta itemprop="addressRegion" content="{{ $city->region->name }}">
                                <meta itemprop="addressLocality" content="{{ $city->name }}">
                                <meta itemprop="streetAddress" content="{{ $firm->address }}">
                                <span class="border-bottom" id="address">{{ $firm->buildingInfo ? $firm->buildingInfo->full_name : $firm->address}}</span>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($firm->stations)
                    <hr class="m-1">
                    <div>
                        <div class="d-inline-block w-25 name align-top">
                            <h3><i class="fal fa-bus-alt"></i> Остановки:</h3>
                        </div>
                        <div class="d-inline-block w-70">
                            @foreach($firm->stations AS $station)
                                <div>
                                    {{ $station['name'] }}
                                    <small class="text-muted"><i class="fal fa-ruler-horizontal"></i> {{ $station['distance'] }}м.</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($firm->groups->count() > 0)
                    <hr class="m-1">
                    <div>
                        <div class="d-inline-block w-25 name align-top">
                            <h3><i class="fal fa-network-wired"></i> Категория:</h3>
                        </div>
                        <div class="d-inline-block w-70">
                            @foreach($firm->groups->reverse()->take(2) AS $group)
                                <div>
                                    <a title="{{ $group->name }} {{ $city->in() }}" href="{{ route('group',[$city->url, $group->url]) }}">
                                        {!! $group->icon !!}  {{ $group->name }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($firm->payments)
                    <hr class="m-1">
                    <div>
                        <div class="d-inline-block w-25 name align-top">
                            <h3><i class="fal fa-money-bill-alt"></i> Оплата:</h3>
                        </div>
                        <div class="d-inline-block h6 pr-1 m-0">
                            @foreach($firm->payments AS $payment)
                                <span title="{{ $payment->name }}">{!! $payment->icon !!}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($keys)
                    <hr class="m-1">
                    <div>
                        <div class="name">
                            <h3><i class="far fa-truck-monster"></i> Новые товары и услуги:</h3>
                        </div>
                        <div class="text-muted small">
                            <ul class="keys-list">
                                @foreach($keys AS $key)
                                    <li>
                                        @if ($loop->iteration == 1)
                                            <a title="{{ $key['name'] }} {{ $city->in() }}" href="{{ route('keyword', [$city->url, $key['url']]) }}">{{ $key['name'] }}</a>
                                        @else
                                            {{ $key['name'] }}
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                @include('ads.yandex.firm-2')

                <hr class="m-1">
                <div class="text-center mt-3 mb-1 mob-none">
                    <a title="Написать отзыв о работе компании" class="btn btn-light btn-sm" href="#reviews">
                        <i class="fas fa-comment-plus"></i> Добавить отзыв
                    </a>
                    <a title="Добавить фотографию" class="btn btn-light btn-sm" href="#photos">
                        <i class="fal fa-camera-retro"></i> Добавить фото
                    </a>
                    <button title="Отправить на Ваш номер смс с контактами компании" class="service-button btn btn-light btn-sm"
                            data-url="{{ route('modal-send-sms', [$firm->id]) }}">
                        <i class="fas fa-mobile-android-alt"></i> SMS с контактами
                    </button>
                </div>

            </div>

            <div class="col-md-5 pt-2 p-0 m-0 clearfix">
                <ul class="list-unstyled text-center">
                    <li class="d-inline-block">
                        <button title="Панорама {{ $firm->address }}" id="panorama" class="btn btn-light btn-sm">
                            <i class="fal fa-street-view"></i> Посмотреть на улицу
                        </button>
                    </li>
                    <li class="d-inline-block">
                        <button title="Qr-код в формате vCard" class="service-button btn btn-light btn-sm" data-url="{{ route('firm-qrcode-page', [$firm->id]) }}">
                            <i class="fal fa-qrcode"></i> Qr-code
                        </button>
                    </li>
                </ul>
                <div class="text-center">
                    <div class="map clearfix  position-relative" id="map">
                        <div itemprop="location" itemscope="" itemtype="http://schema.org/Place">
                            <meta itemprop="name" content="{{ $firm->buildingInfo ? $firm->buildingInfo->purpose_name : $firm->address }}">
                            <meta itemprop="description" content="{{ $firm->address }}">
                            <meta itemprop="telephone" content="{{ $firm->phones->count() > 0 ? $firm->phones->first()->value : '' }}">
                            <div itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress">
                                <meta itemprop="addressCountry" content="Россия">
                                <meta itemprop="addressRegion" content="{{ $city->region->name }}">
                                <meta itemprop="addressLocality" content="{{ $city->name }}">
                                <meta itemprop="streetAddress" content="{{ $firm->address }}">
                            </div>
                            <div itemprop="geo" itemscope="" itemtype="http://schema.org/GeoCoordinates">
                                <meta itemprop="latitude" content="{{ $firm->lat }}">
                                <meta itemprop="longitude" content="{{ $firm->lon }}">
                            </div>
                        </div>
                        <img itemprop="image" class="position-absolute m-auto"
                             alt="{{ $firm->name }} на карте {{ $city->caseEd(RU_RO) }}"
                             title="{{ $firm->name }}, {{ $firm->address }} на карте {{ $city->caseEd(RU_RO) }}"
                             src="{{ $firm->getImg('400,400', 17) }}">
                    </div>
                    <div class="d-block mt-2">
                        <button title="Ваша компания?" class="service-button btn btn-light btn-sm" data-url="{{ route('my-firm', [$firm->id]) }}">
                            <i class="fal fa-briefcase"></i> Вы владелец?
                        </button>
                        <button title="Нашли ошибку?" class="service-button btn btn-light btn-sm" data-url="{{ route('error-firm', [$firm->id]) }}">
                            <i class="fal fa-exclamation-triangle"></i> Ошибка
                        </button>
                    </div>

                </div>

            </div>
        </div>
        <div class="row">

            <div class="col-sm-12" id="worktime">
                @includeWhen($firm->worktime, 'firm.worktime', ['firm' => $firm])
                @include('ads.yandex.firm-3')
            </div>

            @if($posts->count() > 0)
                <div id="posts" class="col-sm-12">
                    <hr class="m-1">
                    <h3><i class="fal fa-newspaper"></i> Новости компании</h3>
                    <div class="row m-2">
                        @foreach($posts AS $n => $post)
                            @continue($n >= 4)
                            <div class="col-sm-6 small clearfix mb-2">
                                <small class="text-muted"><i class="fal fa-clock"></i> {{ $post->date }}</small>
                                @if ($post->preview)
                                    <div class="float-left pr-2">
                                        <img src="{{ $post->preview->path }}"
                                             title="{{ \Illuminate\Support\Str::limit($post->text, 30) }}"
                                             alt="{{ \Illuminate\Support\Str::limit($post->text, 30) }}">
                                    </div>
                                @endif
                                <div>
                                    {!! \Illuminate\Support\Str::limit($post->text, 100) !!}
                                </div>
                            </div>
                        @endforeach
                            @include('ads.yandex.firm-4')
                            <a title="Новости {{ $firm->name }}" class="small text-danger" href="{{ route('firm-posts', [$city->url, $firm->url]) }}">
                            Показать все новости ({{ $posts->count() }})
                        </a>
                    </div>
                </div>
            @endif

            <div class="col-sm-12" id="photos">
                <hr class="m-1">
                <h3><i class="fal fa-images"></i> Фотографии</h3>
                <button title="Загрузить фото" class="service-button btn btn-primary btn-sm" data-url="{{ route('modal-firm-photo', [$firm->id]) }}">
                    <i class="fal fa-camera-retro"></i> Добавить фото
                </button>
                @if($firm->photos->count() > 0)
                    <div class="mt-1">
                        @foreach($firm->photos AS $photo)
                            <a alt="Фото {{ $firm->name }}" title="Фото {{ $firm->name }}" target="_blank" class="m-1" href="{{ $photo->url }}">
                                <img width="100" src="{{ $photo->url }}" alt="">
                            </a>
                        @endforeach
                    </div>
                @else
                    <small class="text-muted">
                        Ещё никто не добавил сюда фото. Вы можете это сделать первым.
                    </small>
                @endif
                <hr class="mb-1">
            </div>
            <div class="row p-3 w-100 bottom-block" id="reviews">
                <div class="col-md-8">
                    <h3><i class="fal fa-comments"></i> Отзывы</h3>
                    @include('comment.form')
                </div>
                @if($near)
                    <div class="col-md-4" id="near">
                        <h3 class="text-center h4">
                            <i class="fal fa-map-marked-alt"></i> @if ($firm->groups->count() > 0) {{ $firm->groups->first()->name }} @endif рядом
                        </h3>
                        <ul class="list-group small">
                            @foreach($near AS $firm_near)
                                <li class="list-group-item p-1 px-2">
                                    <a title="{{ $firm_near->name }} {{ $city->in() }}, {{ $firm_near->address }}" href="{{ route('firm', [$city->url, $firm_near->url]) }}">
                                        <i class="fal fa-tag"></i> {{ $firm_near->name }}
                                    </a>
                                    <div class="text-muted mr-auto small">
                                        <i class="far fa-map-marker-alt"></i> {{ round($firm_near->distance) }}м.
                                        {{ $firm_near->subtitle }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div class="w-100 px-3">
                @include('comment.list')
            </div>
        </div>

    </section>
    <script>
        var Firm = {!! json_encode(['lat' => $firm->lat, 'lon' => $firm->lon, 'name' => $firm->name, 'address' => $firm->address]) !!};
    </script>
@endsection