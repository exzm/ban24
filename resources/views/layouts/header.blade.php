<nav class="navbar navbar-expand-lg navbar-light static-top sb-navbar p-0 site-header mb-2">
    <div class="container">
        <a class="navbar-brand logo" href="{{ !empty($city) ? route('city', [$city->url]) : '/' }}" title="Автопортал {{ !empty($city) ? $city->in() : '' }}">
            <img src="/img/logo.png" title="Автопортал {{ !empty($city) ? $city->in() : '' }}" alt="Автопортал {{ !empty($city) ? $city->in() : '' }}">
        </a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false">
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item mr-3 service-button" data-url="{{ route('modal-city-select') }}">
                    <span class="nav-link">
                        <i class="fal fa-map-marker-alt"></i>
                        <span>{{ !empty($city) ? $city->name : 'Выбрать город' }}</span>
                    </span>
                </li>
                <li class="nav-item service-button" data-url="{{ route('modal-search') }}">
                    <span class="nav-link">
                        <i class="fal fa-search"></i>
                    </span>
                </li>
                @if (Route::has('login'))
                    @auth
                        <li class="nav-item">
                            <a title="Личный кабинет" class="nav-link" href="{{ route('cabinet') }}"><i class="fal fa-user"></i></a>
                        </li>
                        @else
                            <li class="nav-item">
                                <a title="Войти" class="nav-link" href="{{ route('login') }}"><i class="fal fa-sign-in-alt"></i></a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a title="Регистрация на сайте" class="nav-link" href="{{ route('register') }}"><i class="fal fa-user-plus"></i></a>
                                </li>
                            @endif
                            @endauth
                        @endif
                <li class="nav-item">
                    <a title="Добавить компанию" class="nav-link" href="{{ route('add-firm') }}">
                        <i class="fas fa-plus-octagon"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>