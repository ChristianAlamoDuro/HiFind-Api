<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $table  = 'games';
    public $timestamps = false;
    public function categories()
    {
        // Si el nombre de la tabla es diferente a lo predeterminado o el ID de la tabla tiene otro nombre.
        return $this->belongsToMany('App\Category', 'categories_games', 'game_id', 'categorie_id');
    }

    public function marks_games()
    {
        return $this->belongsToMany('\App\User', 'marks_users_games')->withPivot('user_id', 'mark');
    }

    public function mark_user_game()
    {
        return $this->belongsToMany('App\User', 'marks_users_games', 'game_id', 'user_id');
    }
}
