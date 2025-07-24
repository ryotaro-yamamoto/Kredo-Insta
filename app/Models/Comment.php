<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'body',   
        'user_id',
        'post_id',
    ];
    
    // To get the info of the owner of the comment
    public function user(){
        return $this->belongsTo(User::class)->withTrashed();
    }
}
