<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasApiTokens, Notifiable;

    protected $table = 'comments';

    protected $fillable = [
        'comment', 'user_id', 'post_id', 'approval_status', 'published_at'
    ];


    public function commentuser(){
        return $this->belongsTo(User::class);
    }

    public function commentpost(){
        return $this->belongsTo(Post::class,'post_id', 'id');
    }

    public function commentedBy(){
        return $this->belongsTo(User::class,'user_id','id');
    }

}
