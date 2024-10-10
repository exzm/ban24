$(document).ready(function () {
    var ymap;
    var pano;
    $(function () {
        $('#rating').barrating({
            theme: 'fontawesome-stars',
            initialRating: $('[itemprop="ratingValue"]').attr('content'),
            onSelect: function (value) {
                $('#rating').barrating('readonly', true);

                var url = $('#rating').data('url');
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {score: value},
                    success: function () {
                        $('.rating-count').text('Ваша оценка принята, спасибо!')
                    },
                });
            }
        });

        $(document).on('click', '#panorama', function () {
            ymaps.ready(function () {
                if (pano) {
                    return true;
                }
                pano = ymaps.panorama.locate([Firm.lat, Firm.lon]);
                pano.then(
                    function (panoramas) {
                        if (panoramas.length) {
                            if (ymap) {
                                ymap.destroy();
                            }
                            new ymaps.panorama.Player('map', panoramas[0], {
                                direction: [0, -50]
                            });
                        } else {
                            alert("Для заданной точки не найдено ни одной панорамы.");
                        }
                    }
                );
            });
        })

        ymaps.ready(function () {
            if (ymap) {
                return true;
            }
            ymap = new ymaps.Map('map', {
                center: [Firm.lat, Firm.lon],
                zoom: 17
            }, {
                searchControlProvider: 'yandex#search'
            });
            var placemark = new ymaps.Placemark([Firm.lat, Firm.lon], {
                presetStorage: 'islands#blackCircleDotIcon',
                balloonContentHeader: Firm.name,
                balloonContentBody: Firm.address,
                hintContent: Firm.name,
            }, {
                preset: 'islands#blackCircleDotIcon',
            });
            ymap.geoObjects.add(placemark);
        });

        $(document).on('click', '#address, #map', function () {
            ymaps.ready(function () {
                if (ymap) {
                    return true;
                }
                ymap = new ymaps.Map('map', {
                    center: [Firm.lat, Firm.lon],
                    zoom: 17
                }, {
                    searchControlProvider: 'yandex#search'
                });
                var placemark = new ymaps.Placemark([Firm.lat, Firm.lon], {
                    presetStorage: 'islands#blackCircleDotIcon',
                    balloonContentHeader: Firm.name,
                    balloonContentBody: Firm.address,
                    hintContent: Firm.name,
                }, {
                    preset: 'islands#blackCircleDotIcon',
                });
                ymap.geoObjects.add(placemark);
            });
            $('html, body').animate({
                scrollTop: $('#map').offset().top - 10
            }, 300);
        });


        //reviews
        {
            $(document).on('submit', '#reviews form', function (event) {
                var url = $(this).attr('action');
                $('#reviews .errors').html('');
                $.ajax({
                    type: "POST",
                    url: url,
                    data: $(this).serialize(),
                    success: function (data) {
                        $('.comments-list').prepend(data['view']);
                        $('#reviews .errors').append('<div class="alert alert-info" role="alert"><strong>Спасибо!</strong> Отзыв добавлен.</div>').hide().slideDown();
                        $('#reviews [type="text"]').val('');
                        $('#reviews .btn').attr('disabled', true);
                    },
                    error: function (data) {
                        var errors = data.responseJSON;
                        errors = errors.errors;
                        var text = '';
                        for (var key in errors) {
                            var error = errors[key][0];
                            text = text + '<li>' + error + '</li>';
                        }
                        $('#reviews .errors').append('<div class="alert alert-danger"><ul class="p-1 m-1">' + text + '</ul></div>').hide().slideDown();
                    }
                });
                event.preventDefault();
            });


            $(document).on('click', '.plus', function () {
                var button = $(this);
                if (button.hasClass('disabled')) return;
                $.ajax({
                    type: "GET",
                    url: '/plus/' + $(this).data('id'),
                    success: function (data) {
                        button.text('+' + data);
                        button.addClass('disabled');
                    },
                });
            });
            $(document).on('click', '.minus', function () {
                var button = $(this);
                if (button.hasClass('disabled')) return;
                $.ajax({
                    type: "GET",
                    url: '/minus/' + $(this).data('id'),
                    success: function (data) {
                        button.text('-' + data);
                        button.addClass('disabled');
                    },
                });
            });
        }

    });
});