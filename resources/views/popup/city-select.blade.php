<section class="p-2 text-center">
    <div class="form-group">
        <input autocomplete="off" autofocus id="city-select" type="text" class="form-control" placeholder="Начните вводить название города">
    </div>
    <script src="/js/auto-complete.min.js" defer="defer" type="text/javascript"></script>
    <link href="/css/auto-complete.css" type="text/css" rel="stylesheet"/>

    <script>
        var title = 'Найти город';
        var subtitle = '';

        $('#city-select').autoComplete({
            delay: 0,
            minChars: 1,
            source: function (term, response) {
                $.getJSON('/city-search/' + term, {}, function (data) {
                    response(data);
                });
            },
            renderItem: function (item, search) {
                search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
                var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
                return '<div class="autocomplete-suggestion" data-url="' + item['url'] + '">' + item['name'].replace(re, "<b>$1</b>") + '</div>';
            },
            onSelect: function (e, term, item) {
                window.location.href = '/' + item.data('url');
            }
        });
    </script>


</section>