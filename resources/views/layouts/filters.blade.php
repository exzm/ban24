<div data-url="{{ route('group-filter', [$city->url, $group->url]) }}" id="filters" class="ml-2">
    <button title="Найти работающие компании" data-type="open" type="button" class="btn btn-light btn-sm btn-block font-weight-bold">
        <i class="fal fa-clock"></i> Сейчас открыто
    </button>
    <button title="Найти компании рядом с вами" data-type="near" type="button" class="btn btn-light btn-sm btn-block font-weight-bold">
        <i class="fal fa-map-marker-alt"></i> Рядом
    </button>
</div>