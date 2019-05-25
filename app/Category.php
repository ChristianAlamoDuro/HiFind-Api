<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table  = 'categories';
    public $timestamps = false;
    public function games()
    {
        // Si el nombre de la tabla es diferente a lo predeterminado o el ID de la tabla tiene otro nombre.
        return $this->belongsToMany('App\Game', 'categories_games', 'categorie_id', 'game_id');
    }
}
