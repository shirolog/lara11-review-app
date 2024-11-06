<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'books';

    protected $fillable = [

        'title',
        'author',
        'description',
        'image',
        'status',
    ];

    // reviewsとのリレーション関係

    public function reviews(){

        return $this->hasMany(Review::class);
    }
}
