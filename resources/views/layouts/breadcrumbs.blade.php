@if (!empty($breadcrumbs))
    <ul class="breadcrumb clearfix py-1 px-2 mt-2" itemscope itemtype="http://schema.org/BreadcrumbList">
        @foreach($breadcrumbs AS $breadcrumb)
            @if ($loop->last)
                <li class="breadcrumb-item active" itemtype="http://schema.org/ListItem" itemscope="" itemprop="itemListElement">
                    @if ($loop->first)
                        <i class="fal fa-car-side text-muted"></i>
                    @endif
                    {!! $breadcrumb['name'] !!}
                    <meta content="{{ $breadcrumb['url'] }}" itemprop="item">
                    <meta content="{{ strip_tags($breadcrumb['name']) }}" itemprop="name">
                    <meta content="{{ $loop->iteration }}" itemprop="position">
                </li>
            @else
                <li class="breadcrumb-item" itemtype="http://schema.org/ListItem" itemscope="" itemprop="itemListElement">
                    @if ($loop->first)
                        <i class="fal fa-car-side text-muted"></i>
                    @endif
                    <a title="{{ $breadcrumb['title'] }}" href="{{ $breadcrumb['url'] }}">{!! $breadcrumb['name'] !!}</a>
                    <meta content="{{ $breadcrumb['url'] }}" itemprop="item">
                    <meta content="{{ strip_tags($breadcrumb['name']) }}" itemprop="name">
                    <meta content="{{ $loop->iteration }}" itemprop="position">
                </li>
            @endif
        @endforeach
    </ul>
@endif