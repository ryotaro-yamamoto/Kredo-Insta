<?php

// app/Models/AdvertiseInterest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdvertiseInterest extends Model
{
    use HasFactory;

    protected $table = 'interest_advertise'; // テーブル名を明示（Laravelのデフォルトは逆順）

    protected $fillable = [
        'advertise_id',
        'interest_id',
        // 必要があれば他のカラムを追加
    ];

    public function advertise()
    {
        return $this->belongsTo(Advertise::class);
    }

    public function interest()
    {
        return $this->belongsTo(Interest::class);
    }
}