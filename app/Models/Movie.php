<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $table = 'movies';

    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'description',
        'duration',
        'image',
    ];
    
    public function category(){
        return $this->hasMany(Category::class);
    }

    public function showtimes(){
        return $this->hasMany(Showtime::class, 'movie_id');
    }

    public function casts() :array{
        return [
            'category_id' => 'array',
        ];        
    }     
}
