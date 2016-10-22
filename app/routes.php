<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

// www.domain.com/auth?login=<login>&password=<password>&register=1
// www.domain.com/auth?login=<login>&password=<password>
Route::get('auth', function () {
    extract(Input::all());
    if (!isset($login) || !isset($password)) {
        return array('status' => 'Input error');
    }
    if (isset($register) && $register == 1) {
        if (User::whereLogin($login)->count() != 0) {
            return array('status' => 'Login busy already');
        } else {
            //регистрируем пользователя
            $user = new User;
            $user->login = $login;
            $user->password = $password;
            $user->save();
            Auth::login($user, true);
            return Array('status' => 'success', 'id' => Auth::user()->id);
        }
    } else {
        //пытаемся залогинить
        $users = User::whereLogin($login)->get();
        if ($users->count() == 1) {
            $user = $users->first();
        } else {
            return Array('status' => 'Wrong login');
        }
        if ($user && $user->password == $password) {
            if ($user->remember_token) {
                $user->remember_token = null;
                $user->save();
            }
            Auth::login($user, true);
            Auth::user()->touch();
            return Array('status' => 'success', 'id' => Auth::ID(), 'auth_token' => Auth::user()->remember_token);
        } else {
            return Array('status' => 'Wrong password');
        }
        // return Array('status' => 'error', 'id' => 0);
    }
});

Route::get('logout', function () {
    if (Auth::check()) {
        Auth::logout();
        return Array('status' => 'success');
    }
    return Array('status' => '');
});

Route::group(array('before' => 'auth'), function () {

    Route::any('categories', 'CategoryController@index');
    Route::any('categories/add', 'CategoryController@store');
    Route::any('categories/edit', 'CategoryController@edit');
    Route::any('categories/del', 'CategoryController@del');
    Route::any('transcat', 'CategoryController@transcat');

    //“data”: [ {“id”:<id>, “title”: <title>}, ...]
    Route::any('categories/synch', 'CategoryController@synch');
    Route::any('categories/{id}', 'CategoryController@show');

    Route::any('balance', 'UsersController@balance');

    Route::any('transactions', 'OperationsController@index');
    Route::any('transactions/add', 'OperationsController@store');
    Route::any('transactions/edit', 'OperationsController@edit');
    Route::any('transactions/del', 'OperationsController@destroy');
    Route::any('transactions/synch', 'OperationsController@synch');


    // Route::any('transaction/{id}', 'OperationsController@show');
    // Route::any('transaction/edit', 'OperationsController@edit');
});
