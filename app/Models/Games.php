<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
class Games extends Model
{   
    use HasFactory;
   protected $fillable=['name','category_id','type','status','details'];
   public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'game_id');
    }
}
