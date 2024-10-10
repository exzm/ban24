<div class="rating">
    @for ($i = 0; $i < 5; $i++)
        @if ($score > $i)
            <i class="fas fa-star"></i>
        @else
            <i class="far fa-star"></i>
        @endif
    @endfor
</div>