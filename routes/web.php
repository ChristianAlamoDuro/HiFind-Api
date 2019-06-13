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
##################################################################################
##############################   DOCUMENTACIÓN API  ##############################
##################################################################################


Route::post('/users/register', 'UserController@register');
Route::post('/users/login', 'UserController@login');
Route::post('/users/update', 'UserController@update');


#####  DOCUMENTACIÓN PARA LAS RUTAS DE CATEGORÍAS  #####
## Nota para todas las rutas post se tiene que pasar en el json ademas, una key llamada user_id con el id del usuario logueado actualmente para su autenticación ##


# GET /api/categories #
# devuelve todas las categorias y su categoria especial #
#GET /api/categories/{id de categoria}#
#GET /api/categories/{nombre de la categoría especial} ejemplo /is_game#
#POST /api/categories/ json={"name":"simuladores","special_category":"is_game"}#
#POST /api/categories/ le pasas el id el nombre de la categoria y el nombre de la categoría especial ejemplo {"id":1,"name":"ciencia ficcion","special_category":"is_movie"}#
Route::resource('/api/categories', 'CategoryController');


#####  DOCUMENTACIÓN PARA LAS RUTAS DE JUEGOS  #####

# GET /api/games/#
# Ruta que devuelve todos los juegos #
# GET /api/games/{nombre de videojuego} #
# Ruta que devuelve todas las ocurrencias encontradas en la busqueda #
# POST guardar /api/games #
# En el body un json con los valores ejemplo json={"name":"forza horizon 4","sinopsis":"juego de carreras to guapo","duration":"0","out_date":"2019-10-14 00:00:00","public_directed":"+16","image":"not found","categories":[1,2]} #
# POST actualizar /api/games #
# En el body un json con los valores ejemplo pero pasando una key id json={"id":1,"name":"forza horizon 4","sinopsis":"juego de carreras to guapo","duration":"0","out_date":"2019-10-14 00:00:00","public_directed":"+16","image":"not found","categories":[1,2]} #
Route::resource('/api/games', 'GameController');


#####  DOCUMENTACIÓN PARA LAS RUTAS DE PELICULAS  #####

#GET /api/movies
#Ruta que devuelve el listado completo de películas con sus categorías
#GET /api/movies/{titulo de película}
#Ruta que devuelve todas las películas que contengan el titulo introducido con su categoría y puntuación
#POST /api/movies
# Realiza una inserción y enlaza con actores y películas ya existentes.
# Requiere un JSON : json={"title":"pelicula insertada","sinopsis":"esta pelicula se ha insertado","duration":"10","out_date":"2019-10-16","public_directed":"16","image":"not found","film_producer":"productora de peli insertada","categories":[1,2] ,"directors":[1,2] ,"actors":[1,2]}
#Si en el JSON se incluye un campo id ("id":1) en lugar de introducir datos actualiza la película con dicho id
Route::resource('/api/movies', 'MovieController');


#####  DOCUMENTACIÓN PARA LAS RUTAS DE ACTORES  #####

#GET /api/actors
#Retorna lista completa de actores
#GET /api/actors/nombre-apellido
#Busca actores por nombre y apellido. Es necesario pasarle por GET los datos introducidos por el usuario cambiando el elemento separador ESPACIO (' ') por GUION ('-')
#POST /api/actors
#Requiere un JSON: json={"id":"1","name":"modificado name", "surname":"modificado surname","birthday":"2019-10-16","biography":"modificada biografy","image":"not found"}
#Si en el JSON llega un campo ID búsca el actor con dicho ID y lo actualiza, si no llega id lo inserta como nuevo actor
Route::resource('/api/actors', 'ActorController');


#####  DOCUMENTACIÓN PARA LAS RUTAS DE DIRECTORES  #####

#GET /api/directors
#Retorna lista completa de directores
#GET /api/directors/nombre-apellido
#Busca directores por nombre y apellido. Es necesario pasarle por GET los datos introducidos por el usuario cambiando el elemento separador ESPACIO (' ') por GUION ('-')
#POST /api/directors
#Requiere un JSON: json={"id":"1","name":"modificado name", "surname":"modificado surname","birthday":"2019-10-16","biography":"modificada biografy","image":"imageModificada.png"}
#Si en el JSON llega un campo ID búsca el actor con dicho ID y lo actualiza, si no llega id lo inserta como nuevo director
Route::resource('/api/directors', 'DirectorController');



#####  DOCUMENTACIÓN PARA LAS RUTAS DE CATEGORÍAS ESPECIALES  #####

# GET /api/special_category_games/{nombre de la categoria especial que quieres que sea true a filtrar} #
# GET /api/category_games/{nombre de la categoria a filtrar} #
Route::resource('/api/special_category_games','SpecialCategoryGameController');
Route::resource('/api/category_games','CategoryGameController');


#GET /api/special_category_movies/{nombre categoría especial}
#Retorna los casos en los cuales la categoria enviada por get tiene valor true
Route::resource('/api/special_category_movies','SpecialCategoryMovieController');


#GET /api/category_movies/{nombre de la categoría}
#Retornará las películas con la categoría enviada por GET
Route::resource('/api/category_movie','CategoryMovieController');


#####  DOCUMENTACIÓN PARA LA DE VOTACIONES  #####

# POST /api/mark_game/json={"mark":5,"game_id":2,"user_id":1} #
Route::resource('/api/mark_game', 'MarkGameController');

#POST /api/mark_movie/
#Recive un JSON: json={"movie_id":1,"user_id":1,"mark":10}
Route::resource('/api/mark_movie', 'MarkMovieController');


#####  DOCUMENTACIÓN PARA LAS RUTAS DE BORRADO DE DATOS  #####

# POST /api/delete/category/ id de categoria a eliminar ejemplo  {"id":1}# 
# POST /api/delete/game/ id de juego a eliminar ejemplo  {"id":1} #
Route::resource('/api/delete/game', 'DeleteGameController');
Route::resource('/api/delete/category', 'DeleteCategoryController');


#POST /api/delete/movie 
#Recibe un JSON con el id de la película que se desea eliminar
Route::resource('/api/delete/movie', 'DeleteMovieController');


#POST /api/delete/actor 
#Recibe un JSON con el id del actor que se desea eliminar
Route::resource('/api/delete/actor', 'DeleteActorController');

#POST /api/delete/director 
#Recibe un JSON con el id del director que se desea eliminar
Route::resource('/api/delete/director', 'DeleteDirectorController');