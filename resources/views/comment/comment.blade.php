<div itemprop="review" itemscope itemtype="http://schema.org/Review" id="comment-{{ $review->id }}" class="media list-group-item mb-1">
    <meta itemprop="itemReviewed" content="{{ !empty($title) ? $title : '' }}">
    <div class="media-body">
        <div class="row">
            <h4 itemprop="author" class="media-heading user_name h6 text-success col-9">
                <i class="fal fa-user"></i> {{ $review->user }}
            </h4>
            <div class="col-3 text-muted small text-right">
                <i class="fal fa-clock"></i>
                <time itemprop="datePublished">{{ $review->created_at ?: date('Y-d-m H:i:s') }}</time>
            </div>
        </div>
        <div itemprop="description" class="small text-muted">
            <i class="fal fa-comment-dots"></i> {{ $review->comment }}
        </div>
    </div>
    <div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
        <meta itemprop="worstRating" content="1">
        <meta itemprop="ratingValue" content="{{ $review->score }}">
        <meta itemprop="bestRating" content="5">
    </div>
</div>