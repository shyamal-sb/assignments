<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Post extends Model
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'title', 'content', 'user_id',
    ];   
}
