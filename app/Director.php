<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Director extends Model
{
    protected $table  = 'directors';
    public $timestamps = false;
   
    public function movies()
    {
        return $this->belongsToMany('App\Movie', 'directors_movies', 'director_id', 'movie_id');
    }
}