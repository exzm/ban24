@php
    $title= '404 Страница не найдена';
@endphp
@extends('layouts.core')

@section('content')
    <style>
        .error-template {padding: 40px 15px;text-align: center;}
        .error-actions {margin-top:15px;margin-bottom:15px;}
        .error-actions .btn { margin-right:10px; }
    </style>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="error-template">
                    <h1>
                        Ой!</h1>
                    <h2>
                        500 сервис недоступен</h2>
                    <div class="error-details">
                        К сожалению, произошла ошибка.
                        Cервер временно не имеет возможности обрабатывать запросы по техническим причинам.
                    </div>
                    <div class="error-actions">
                        <a href="{{ route('front') }}" class="btn btn-primary btn-lg">
                            <span class="glyphicon glyphicon-home"></span>
                            Перейти на главную
                        </a>
                        <a href="mailto:{{ env('APP_EMAIL') }}" class="btn btn-default btn-lg">
                            <span class="glyphicon glyphicon-envelope"></span> Обратная связь
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection