<?php

// app/Models/Advertise.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Advertise extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'image',
        'advertiser_id',
    ];

    // 興味との多対多リレーション（中間テーブル: interest_advertise）
    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'interest_advertise');
    }

    // 広告主とのリレーション（ユーザーモデル）
    public function advertiser()
    {
        return $this->belongsTo(User::class, 'advertiser_id');
    }
}


