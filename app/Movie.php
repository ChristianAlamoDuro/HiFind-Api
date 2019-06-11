<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $table  = 'movies';
    public $timestamps = false;
    
    public function categories_movies()
    {
        return $this->belongsToMany('App\Category', 'categories_movies', 'movie_id', 'category_id');
    }

    public function marks_movies()
    {
        return $this->belongsToMany('App\User','marks_users_movies')->withPivot('user_id', 'mark');

    }
    public function directors_movies()
    {
        return $this->belongsToMany('App\Director', 'directors_movies', 'movie_id', 'director_id');
    }

    public function actors_movies()
    {
        return $this->belongsToMany('App\Actor', 'actors_movies', 'movie_id', 'actor_id');
    }
}