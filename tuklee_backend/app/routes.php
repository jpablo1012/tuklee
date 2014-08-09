<?php
header('Access-Control-Allow-Origin: *');
/*header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: *");*/
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


Route::get('users/register', 'UserController@register');
Route::get('users/login/{username}/{password}', 'UserController@login');

Route::get('/', function()
{
	return View::make('hello');
});

Route::group(array('before' => 'authentication'), function(){
	Route::get('events/create', 'EventoController@create');
	Route::get('events/getInfoEvent/{id_event}', 'EventoController@getInfoEvent');
	Route::get('events/enterEvent', 'EventoController@enterEvent');
	Route::get('events/getListPeopleInsideEvent', 'EventoController@getListPeopleInsideEvent');

	Route::get('users/profileIsCompleted', 'UserController@profileIsCompleted');
	Route::get('users/createCard', 'UserController@createCard');

	Route::get('shareCards/requestExchange/{id_user}', 'ShareCardController@requestExchange');  
	Route::get('shareCards/responseExchange/{share_card_id}/{status}', 'ShareCardController@responseExchange');
	Route::get('shareCards/getCardsToResponse', 'ShareCardController@getCardsToResponse');
});
