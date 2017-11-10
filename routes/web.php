<?php

use App\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('twitter/login', ['as' => 'twitter.login', function () {
    // your SIGN IN WITH TWITTER  button should point to this route
    $sign_in_twitter = true;
    $force_login = false;

    // Make sure we make this request w/o tokens, overwrite the default values in case of login.
    Twitter::reconfig(['token' => '', 'secret' => '']);
    $token = Twitter::getRequestToken(route('twitter.callback'));

    if (isset($token['oauth_token_secret'])) {
        $url = Twitter::getAuthorizeURL($token, $sign_in_twitter, $force_login);

        Session::put('oauth_state', 'start');
        Session::put('oauth_request_token', $token['oauth_token']);
        Session::put('oauth_request_token_secret', $token['oauth_token_secret']);

        return Redirect::to($url);
    }

    return Redirect::route('twitter.error');
}]);

Route::get('login', function () {
    return '<a href="' . route('twitter.login') . '">Log in with Twitter</a>';
})->name('login')->middleware('guest');

Route::get('dashboard', function () {
    $tweets = Cache::remember('tweets::user-' . auth()->id(), 5, function () {
        return Twitter::getHomeTimeline(['count' => 20]);
    });

    return view('dashboard')->with('tweets', $tweets);
})->name('dashboard')->middleware('auth');

Route::get('twitter/callback', ['as' => 'twitter.callback', function () {
    // You should set this route on your Twitter Application settings as the callback
    // https://apps.twitter.com/app/YOUR-APP-ID/settings
    if (Session::has('oauth_request_token')) {
        $request_token = [
            'token'  => Session::get('oauth_request_token'),
            'secret' => Session::get('oauth_request_token_secret'),
        ];

        Twitter::reconfig($request_token);

        $oauth_verifier = false;

        if (Request::has('oauth_verifier')) {
            $oauth_verifier = Request::get('oauth_verifier');
            // getAccessToken() will reset the token for you
            $token = Twitter::getAccessToken($oauth_verifier);
        }

        if (!isset($token['oauth_token_secret'])) {
            return Redirect::route('twitter.error')
                ->with('flash_error', 'We could not log you in on Twitter.');
        }

        $credentials = Twitter::getCredentials();

        if (is_object($credentials) && !isset($credentials->error)) {
            // $credentials contains the Twitter user object with all the info about the user.
            // Add here your own user logic, store profiles, create new users on your tables...you name it!
            // Typically you'll want to store at least, user id, name and access tokens
            // if you want to be able to call the API on behalf of your users.

            // This is also the moment to log in your users if you're using Laravel's Auth class
            // Auth::login($user) should do the trick.
            $user = User::firstOrCreate(
                ['user_id' => $token['user_id']],
                [
                    'oauth_token' => $token['oauth_token'],
                    'oauth_token_secret' => $token['oauth_token_secret'],
                    'user_id' => $token['user_id'],
                    'screen_name' => $token['screen_name'],
                ]
            );

            Auth::login($user);

            Session::put('access_token', $token);

            return Redirect::to(route('dashboard'))
                ->with('flash_notice', 'Congrats! You\'ve successfully signed in!');
        }

        return Redirect::route('twitter.error')
            ->with('twitter_error', $credentials->error)
            ->with('flash_error', 'Crab! Something went wrong while signing you up!');
    }
}]);

Route::get('twitter/error', ['as' => 'twitter.error', function () {
    return "<h1>ERROR WILL ROBINSON</h1><p>" . session('flash_error') . "</p><p>" . session('twitter_error') . "</p>";

    // Something went wrong, add your own error handling here
}]);

Route::get('twitter/logout', ['as' => 'twitter.logout', function () {
    Session::forget('access_token');
    return Redirect::to('/')
        ->with('flash_notice', 'You\'ve successfully logged out!');
}]);
