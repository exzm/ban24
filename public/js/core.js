if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register(
        '/sw.js'
    ).then(function () {
        console.log('ServiceWorker registration');
    }).catch(function (error) {
        console.error('ServiceWorker error: ' + error);
    });
}

var isMob = window.innerWidth < 500;

$(document).ready(function () {
    var ymap;
    var iam;
    var filters = {near: 0, open: 0, lat: 0, lon: 0, page: 0};


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    if (!isMob) {
        $(".stick").stick_in_parent()
            .on("sticky_kit:stick", function (e) {
                console.log("has stuck!", e.target);
            })
            .on("sticky_kit:unstick", function (e) {
                console.log("has unstuck!", e.target);
            });
    }


    /*Фильтры*/
    $(document).on('click', '#filters button', function () {
        var type = $(this).data('type');
        filters[type] ^= true;
        var url = $('#filters').data('url');
        $(this).toggleClass('active');
        $(".firm-list").css('opacity', '0.5');
        if (filters.near) {
            map();
            ymaps.geolocation.get({
                mapStateAutoApply: true
            }).then(function (result) {
                iam = result.geoObjects;
                filters.lon = result.geoObjects.position[1];
                filters.lat = result.geoObjects.position[0];
                getList(filters, url);
            });
        } else {
            getList(filters, url);
        }
    });

    $(document).on('click', '.firm-list .page-link', function () {
        var url = $('#filters').data('url');
        $(".firm-list").css('opacity', '0.5');
        filters['page'] = $(this).text();
        getList(filters, url);
        scrollTo('h1');
        return false;
    });


    function getList(filters, url) {
        $.ajax({
            type: "POST",
            url: url,
            data: filters,
            success: function (data) {
                $(".firm-list").replaceWith(data);
                $(".firm-list").css('opacity', '1')
                map();
            },
        });
    }

    /*Модальные окна*/
    $(document).on('click', '.service-button', function (event) {
        event.preventDefault();
        $("#modal").iziModal('destroy')
        var url = $(this).data('url');
        $("#modal").iziModal({
            onOpening: function (modal) {
                modal.startLoading();
                $.get(url, function (data) {
                    $("#modal .iziModal-content").html(data);
                    $('#modal').iziModal('setTitle', title);
                    $('#modal').iziModal('setSubtitle', subtitle);
                    modal.stopLoading();
                });
            }
        });

        $("#modal").iziModal('open');
    })

    /*Карта*/
    $(document).on('click', '.show-map, .map-img', function () {
        map();
    });

    $('.show-map, .map-img').click();

    function map() {
        $('.map').addClass('map-init');
        var url = $('#map').data('url');
        $.post(url, filters, function (markers) {
            ymaps.ready(function () {
                if (ymap) {
                    ymap.destroy();
                }
                ymap = new ymaps.Map('map', {
                    center: [City.lat, City.lon],
                    zoom: 11
                }, {
                    searchControlProvider: 'yandex#search'
                });
                var objectManager = new ymaps.ObjectManager({
                    clusterize: true,
                    clusterDisableClickZoom: true
                });
                objectManager.objects.options.set('preset', 'islands#blackCircleDotIcon');
                objectManager.clusters.options.set('preset', 'islands#blackClusterIcons');
                ymap.geoObjects.add(objectManager);
                objectManager.add(markers);
                if (iam) {
                    ymap.geoObjects.add(iam);
                }
            });
        });
    }

    $('a[href^="#"]').click(function () {
        var element = $(this).attr("href");
        scrollTo(element);
        blink(element, 2, 400);
        return false;
    });

    function scrollTo(element) {
        $('html, body').animate({
            scrollTop: $(element).offset().top - 10
        }, 300);
    }

    function blink(elem) {
        $(elem).fadeOut(200).fadeIn(200).fadeOut(200).fadeIn(200);
    }

    $(window).bind("load", function () {
        if (window.location.hash == '#opennear') {
            $('[data-type="open"], [data-type="near"]').click();
        }
    });

});