<form class="alert alert-dark" id="reviews" method="POST" action="{{ route('comment-store') }}">
    <div class="form-group">
        <div id="score"></div>
    </div>
    <div class="errors">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul class="p-1 m-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    <div class="form-group">
        <input name="user" required type="text" class="form-control" placeholder="Ваше имя">
    </div>
    <div class="form-group">
        <textarea name="comment" required class="form-control" id="text" placeholder="Что Вы думаете о компании «{{ $firm->name }}»?"></textarea>
    </div>
    {{ csrf_field() }}
    <input type="hidden" name="firm_id" value="{{ $firm->id }}">
    <input type="hidden" name="city_id" value="{{ !empty($city) ? $city->id : '' }}">
    <button type="submit" class="btn btn-light">Отправить</button>
</form>