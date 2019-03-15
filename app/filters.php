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
	//
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

Route::filter('auth', function(){
    $backend_token = Session::get('backend_token');
    Log::info('auth#'.$backend_token);
    if(empty($backend_token)){
        return Redirect::guest('login');
    }else{
        $user = new SysUser();
        $obj = json_decode(base64_decode($backend_token));
        $user_ret =$user->getUser($obj->user_name);
        if(empty($user_ret)){
            return Redirect::guest('login');
        }else{
            $temp_time=date("Y-m-d H:i:s",time());
            if($obj->expiresAt<=$temp_time){
                return Redirect::guest('login');
            }
        }

        $token=array();
        $token['user_name']= $obj->user_name;
        $token['real_name']= $obj->real_name;
        $token['user_id']= $obj->user_id;
        $token['expiresAt']= date("Y-m-d H:i:s",time()+600000);
        Session::put('backend_token', base64_encode(json_encode($token)));
    }
});


Route::filter('auth.basic', function()
{
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
