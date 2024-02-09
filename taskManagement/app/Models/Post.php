<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'title', 'content', 'user_id', 'published_at'
    ];   
}
