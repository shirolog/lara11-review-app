<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';

    protected $fillable = [

        'review',
        'rating',
        'user_id',
        'book_id',
        'status',
    ];


    //userとのリレーション関係

    public function user(){

        return $this->belongsTo(User::class);
    }

    //bookとのリレーション関係
    public function book(){

        return $this->belongsTo(Book::class);
    }
}
