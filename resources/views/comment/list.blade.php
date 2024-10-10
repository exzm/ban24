@if ($reviews)
    <div class="comments-list list-group">
        @each('comment.comment', $reviews, 'review')
    </div>
@endif

