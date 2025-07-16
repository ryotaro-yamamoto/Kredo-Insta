<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryPost extends Model
{
    protected $table = 'category_post';// Define the pivot table name

    protected $fillable = [
        'category_id',
        'post_id',
    ];// Define fillable attributes

    public $timestamps = false;

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function post(){
        return $this->belongsTo(Post::class);
    }
}
