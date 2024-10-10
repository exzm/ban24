<div class="firm-list">
    @if ($firms->count() > 0)
        @foreach($firms AS $firm)
            @if ($loop->iteration == 1)
                <div class="row row-striped mb-1 border">
                    @include('ads.yandex.firm-list-1')
                </div>
            @endif
            @if ($loop->iteration == 3)
                <div class="row row-striped mb-1 border">
                    @include('ads.yandex.firm-list-2')
                </div>
            @endif
            @if ($loop->iteration == 6)
                <div class="row row-striped mb-1 border">
                    @include('ads.yandex.firm-list-3')
                </div>
            @endif
            @include('firm.firm_in_list', ['firm' => $firm])
        @endforeach
        <div class="text-center small mt-2">
            {{ !empty($pager) ? $pager : '' }}
        </div>
    @else
        <div class="text-center">
            <h3 class="p-2">Ничего не найдено!</h3>
        </div>
    @endif
</div>

