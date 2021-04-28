<?php
    
    Route::get('/', function () {
        return view('welcome');
    });
    
    // 未定義のURLを直打ちされたらホームに飛ばす何かが必要？

    
    // ユーザ登録
    Route::get('signup', 'Auth\RegisterController@showRegistrationForm')->name('signup.get');
    Route::post('signup', 'Auth\RegisterController@register')->name('signup.post');
    
    
    // 認証
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login')->name('login.post');
    Route::get('logout', 'Auth\LoginController@logout')->name('logout.get');
    
    
    // この表記だとすべてのユーザーがすべてのタスクに触れてしまう
    // TasksController側で規制をかける必要がある
    Route::resource('tasks', 'TasksController'); 


    Route::fallback(function(){
        return redirect(route('/')); // どのルートにも一致しない場合にさせたい処理
    });
    
    // Route::group(['middleware' => ['auth']], function () {
    //     // 全部書いたらonlyの意味がない
    //     Route::resource('tasks','TasksController',['only' => ['edit','store','destroy']]);
    //});
