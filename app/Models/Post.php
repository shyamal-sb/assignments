<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasApiTokens, Notifiable;

    protected $table = 'posts';

    protected $fillable = [
        'title', 'content', 'user_id', 'published_at'
    ];  
    

    public function postComments(){
        return $this->hasMany(Comment::class);
    }
    

    public function commentedBy(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    
    public function postedBy(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
