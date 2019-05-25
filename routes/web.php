<?php

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


Route::post('/users/register', 'UserController@register');
Route::post('/users/login', 'UserController@login');
Route::post('/users/update', 'UserController@update');

#GET /api/games/
#Ruta que devuelve todos los juegos

#GET /api/games/{nombre de videojuego}
#Ruta que devuelve todas las ocurrencias encontradas en la busqueda

#POST /api/games
#En el body un json con los valores ejemplo json={"name":"forza horizon 4","sinopsis":"juego de carreras to guapo","duration":"0","out_date":"2019-10-14 00:00:00","public_directed":"+16","image":"not found","categories":[1,2]}

#PUT /api/games/{id del juego}
#En el body le pasas un json con los parametros a actualizar

Route::resource('/api/categories', 'CategoryController');
Route::resource('/api/games', 'GameController');