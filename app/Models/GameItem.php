<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameItem extends Model
{

    use HasFactory;
    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }

    protected $fillable=['name','type'];
}
