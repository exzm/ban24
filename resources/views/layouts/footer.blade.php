<footer class="footer">
    <div class="container">
        <div class="text-muted float-left small description">
            <i class="fal fa-car-mechanic"></i> {{ !empty($footer) ? $footer : 'АвтоБАН' }}
        </div>
        <div class="float-right small text-muted">
            <a class="mr-1" title="Обратная связь" href="{{ route('feedback-page') }}">
                <i class="fas fa-comment"></i>
            </a>
            <i class="fal fa-copyright"></i> <time content="{{ date('Y-m-d\TH:i:sP') }}">2016-{{ date('Y') }}</time> Ban24.ru
        </div>
    </div>
</footer>