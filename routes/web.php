<?php

Auth::routes();

//Коментарии
Route::get('plus/{id}', 'CommentController@plus')->where('id', '[0-9]+')->name('comment-plus');
Route::get('minus/{id}', 'CommentController@minus')->where('id', '[0-9]+')->name('comment-minus');;
Route::post('comment/store', 'CommentController@submitForm')->name('comment-store');
Route::get('comment/delete/{id}', 'CommentController@delete')->name('comment-delete');
Route::get('comment/edit/{id}', 'CommentController@edit')->name('comment-edit');

Route::get('/cabinet', 'FirmController@sendSms')->name('cabinet');
Route::get('/new', 'FirmController@sendSms')->name('add-firm');

Route::get('feedback', 'PageController@feedback')->name('feedback-page');
Route::post('feedback', 'PageController@feedback')->name('feedback-post');

Route::post('/firm/upload/{firm}', 'FirmController@uploadPhoto')->name('upload-firm-photo');
Route::get('/firm/upload/{firm}', 'FirmController@uploadPhoto')->name('modal-firm-photo');
Route::get('/firm/send-sms/{firm}', 'FirmController@sendSms')->name('modal-send-sms');
Route::get('/firm/route/{firm}', 'FirmController@route')->name('modal-route');
Route::get('/firm/error/{firm}', 'FirmController@error')->name('error-firm');
Route::get('/firm/my/{firm}', 'FirmController@myFirm')->name('my-firm');
Route::get('/firm/qrcode/{firm}', 'FirmController@qrcodePage')->name('firm-qrcode-page');
Route::get('/firm/qrcode/{firm}/img', 'FirmController@qrcode')->name('firm-qrcode-img');
Route::post('firm/rating/{firm}', 'FirmController@rating')->name('firm-rating-post');

Route::get('search', 'SearchController@index')->name('modal-search');
Route::get('city-select', 'CityController@select')->name('modal-city-select');
Route::get('city-search/{str}', 'CityController@search')->name('city-search');

Route::get('/', 'CityController@cityPage')->name('front');
Route::get('/{city}', 'CityController@cityPage')->name('city');
Route::get('/{city}/{group}', 'GroupController@groupPage')->name('group');
Route::post('/{city}/group-filter/{group}', 'GroupController@filter')->name('group-filter');
Route::post('/{city}/group-markers/{group}', 'GroupController@filter')->name('group-markers');

Route::get('/{city}/service/{keyword}', 'KeywordController@keywordPage')->name('keyword');
Route::post('/{city}/keyword-filter/{keyword}', 'KeywordController@filter')->name('keyword-filter');
Route::post('/{city}/keyword-markers/{keyword}', 'KeywordController@filter')->name('keyword-markers');

Route::get('/{city}/firm/{firm}', 'FirmController@firmPage')->name('firm');
Route::get('/{city}/firm/{firm}/news', 'PostController@postsPage')->name('firm-posts');




