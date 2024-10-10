@if($comments->count() > 0)
    <div class="p-2 mt-2">
        <h2 class="h5 text-center"><i class="fal fa-comment-smile"></i> Новые отзывы</h2>
        <ul class="list-unstyled">
            @foreach($comments AS $comment)
                <li class="ml-1">
                    <small class="text-muted d-block">
                        <a href="{{ route('firm', [$city->url, $comment->firm->url]) }}#comment-{{ $comment->id }}">
                            <i class="fal fa-comment"></i> {{ $comment->firm->name }}
                        </a>
                    </small>
                    <small>
                        {{ \Illuminate\Support\Str::limit($comment->comment, 100) }}
                    </small>
                    <hr>
                </li>
            @endforeach
        </ul>
    </div>
@endif