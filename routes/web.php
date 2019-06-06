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

#GET /api/categories
#devuelve todas las categorias y su categoria especial
#GET /api/categories/{id de categoria}
#GET /api/categories/{nombre de la categoría especial} ejemplo /is_game
#POST /api/categories/json={"name":"simuladores","special_category":"is_game"}
#POST /api/categories/ le pasas el id el nombre de la categoria y el nombre de la categoría especial ejemplo {"id":1,"name":"ciencia ficcion","special_category":"is_movie"}


Route::resource('/api/categories', 'CategoryController');

#GET /api/games/
#Ruta que devuelve todos los juegos

#GET /api/games/{nombre de videojuego}
#Ruta que devuelve todas las ocurrencias encontradas en la busqueda

#POST /api/games
#En el body un json con los valores ejemplo json={"name":"forza horizon 4","sinopsis":"juego de carreras to guapo","duration":"0","out_date":"2019-10-14 00:00:00","public_directed":"+16","image":"not found","categories":[1,2]}


#PUT /api/games/{id del juego}
Route::resource('/api/games', 'GameController');

#GET /api/special_category_games/{nombre de la categoria especial que quieres que sea true a filtrar}
#GET /api/category_games/{nombre de la categoria a filtrar}
Route::resource('/api/special_category_games','SpecialCategoryGameController');
Route::resource('/api/category_games','CategoryGameController');

#POST /api/mark_game/json={"mark":5,"game_id":2,"user_id":1}
Route::resource('/api/mark_game', 'MarkGameController');

#POST /api/delete/category/ id de categoria a eliminar ejemplo  {"id":1}
#POST /api/delete/game/ id de juego a eliminar ejemplo  {"id":1}
Route::resource('/api/delete/game', 'DeleteGameController');
Route::resource('/api/delete/category', 'DeleteCategoryController');



Route::resource('/api/movies', 'MovieController');