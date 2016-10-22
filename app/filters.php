<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
 		header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
    header('Access-Control-Allow-Credentials: true');
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	// $token = Input::get('auth_token');
	$token =  Request::header('authToken');

	if($token){
		$users = User::where('remember_token', $token)->get();
		if(count($users) == 1){
			// $lifetime = Config::get('session.lifetime');
			// $users[0]->lifetime
			// $updated_at = date_timestamp_get($users[0]->updated_at);
			// $now = date_timestamp_get (new DateTime());
			// if(Config::get('session.lifetime') < ($now-$updated_at)/60){
			// 	$users[0]->remember_token = NULL;
			// 	$users[0]->save();
			// 	return Response::make(array('status'=>'Session timeout'), 401);
			// }
			// else
			// {
				Auth::login($users[0]);
				Auth::user()->touch();
			// }
		}else{
			return Response::make(array('status'=>'Wrong token'), 401);
		}
	}else	if (Auth::guest())
	{
		return Response::make(array('status'=>'unauthorized'), 401);
	}
});


Route::filter('auth.basic', function()
{
  // return Auth::onceBasic();
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() !== Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});
