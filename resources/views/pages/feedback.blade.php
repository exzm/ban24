@extends('layouts.core')

@section('content')
    <h1><i class="fas fa-comments"></i> Обратная связь</h1>

    <hr>
    <form method="post" action="{{ route('feedback-post') }}">
        <div class="form-group">
            <label for="name"><i class="fas fa-user"></i> Имя</label>
            <input  required type="text" class="form-control" id="name" placeholder="Ваше имя" name="name">
        </div>
        <div class="form-group">
            <label for="email"><i class="fas fa-at"></i> Email</label>
            <input required type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
        </div>
        <div class="form-group">
            <label for="text"><i class="fas fa-comment-alt"></i> Сообщение</label>
            <textarea required rows="10" class="form-control" id="text" name="text" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Отправить</button>

        {{ csrf_field() }}
    </form>
@endsection