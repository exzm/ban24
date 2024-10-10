<section class="p-2 text-center">
    <div id="route-map" style="height: 400px; width: 100%"></div>
    <script>
        var title = 'Проезд до компании {{ $firm->name }}';
        var subtitle = '{{ $firm->name }}';
        ymap = new ymaps.Map('route-map', {
            center: [{{ $firm->lat }}, {{ $firm->lon }}],
            zoom: 17,
            controls: ['routePanelControl', 'geolocationControl', 'trafficControl', 'fullscreenControl']
        }, {
            searchControlProvider: 'yandex#search'
        });
        var control = ymap.controls.get('routePanelControl');

        control.routePanel.state.set({
            toEnabled: true,
            to: [{{ $firm->lat }}, {{ $firm->lon }}]
        });


    </script>
</section>