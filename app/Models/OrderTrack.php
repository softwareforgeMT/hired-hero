<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTrack extends Model
{
    use HasFactory;
    protected $fillable = ['order_id', 'title','text'];

    public function order()
    {
        return $this->belongsTo('App\Models\Order','order_id');
    }

}
