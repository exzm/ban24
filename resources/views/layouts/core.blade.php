<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <meta name="description" content="{{ !empty($description) ? $description : '' }}"/>
    <meta name="keywords" content="{{ !empty($keywords) ? $keywords : '' }}"/>

    @if (!empty($canonical))
        <link rel="canonical" href="{{ $canonical }}"/>
    @endif
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <meta http-equiv="x-dns-prefetch-control" content="on">
    <link rel="dns-prefetch" href="//mc.yandex.ru">
    <link rel="dns-prefetch" href="//api-maps.yandex.ru">
    <link rel="dns-prefetch" href="//an.yandex.ru">
    <link rel="dns-prefetch" href="//mds.yandex.net">
    <link rel="dns-prefetch" href="//yastatic.net">

    <link rel="apple-touch-icon" sizes="180x180" href="/img/icons/apple-touch-icon.png">
    <link rel="manifest" href="/img/icons/site.webmanifest">
    <link rel="mask-icon" href="/img/icons/safari-pinned-tab.svg" color="#00269d">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="icon" href=​"https://ban24.ru/favicon.ico" type="image/x-icon">
    <meta name="msapplication-TileColor" content="#d9f6f5">
    <meta name="msapplication-config" content="/img/icons/browserconfig.xml">
    <meta name="theme-color" content="#bdd6ff">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    {!! Assets::css() !!}
    @if (isset($og))
        {!! $og->renderTags() !!}
    @endif
</head>
<body>
@include('layouts.header')
<div role="main" class="container">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @include('layouts.breadcrumbs')
    <div class="content">
        @yield('content')
    </div>
</div>
@include('layouts.footer')
<div id="modal" data-iziModal-fullscreen="true" data-iziModal-title="" data-iziModal-subtitle="" data-iziModal-icon="icon-home"></div>
{!! Assets::js(['defer']) !!}
<!-- Yandex.Metrika counter --> <script type="text/javascript" > (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)}; m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)}) (window, document, "script", "https://cdn.jsdelivr.net/npm/yandex-metrica-watch/tag.js", "ym"); ym(51638795, "init", { id:51638795, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); </script> <noscript><div><img src="https://mc.yandex.ru/watch/51638795" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->
</body>
</html>