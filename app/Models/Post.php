<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
class Post extends Model
{
    use HasFactory, Notifiable, SoftDeletes;
    public function categoryPost(){
        return $this->hasMany(CategoryPost::class);
    }

    // A post belongs to a user
    // To get the user who created the post
    public function user(){
        return $this->belongsTo(User::class)->withTrashed();
    }

    // To get the comments of the post
    public function comments(){
        return $this->hasMany(Comment::class);
    }

    // To get the likes of the post
    public function likes(){
        return $this->hasMany(Like::class);
    }

    // returns true if the a user has liked the post
    public function isLiked(){
        return $this->likes()->where('user_id', Auth::user()->id)->exists();
    }

    public function categories(){
        return $this->belongsToMany(Category::class, 'category_post', 'post_id', 'category_id');
    }

    public function images(){
        return $this->hasMany(PostImage::class);
    }

}
