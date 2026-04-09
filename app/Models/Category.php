<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{   
    use HasFactory;
    protected $fillable=['name','status','details','parent_id','position'];
    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }

    public function games()
    {
        return $this->hasMany(Games::class, 'category_id');
    }
    public function activegames()
    {
        return $this->hasMany(Games::class, 'category_id')->where('status',1);
    }

    // public function childes()
    // {
    //     return $this->hasMany(Category::class, 'parent_id');
    // }

    // public function parent()
    // {
    //     return $this->belongsTo(Category::class, 'parent_id');
    // }

}
