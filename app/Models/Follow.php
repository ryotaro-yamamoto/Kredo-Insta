<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    public $timestamps = false;
    protected $primaryKey = ['follower_id', 'following_id'];
    public $incrementing = false;

    // To get the info of a follower
    public function follower(){
        return $this->belongsTo(User::class, 'follower_id')->withTrashed();
    }

    // To get the info of a user that is being followed
    public function following(){
        return $this->belongsTo(User::class, 'following_id')->withTrashed();
    }
}
