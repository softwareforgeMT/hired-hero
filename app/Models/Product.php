<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Games;
class Product extends Model
{   

    use HasFactory;
    public function scopeActive($query)
    {
        return $query->where('status', '=', 1)->where('is_closed',0);
    }
    public function  game()
    {
        return $this->belongsTo(Games::class, 'game_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    protected $fillable=['delivery_time','delivery_type','price'];
}
