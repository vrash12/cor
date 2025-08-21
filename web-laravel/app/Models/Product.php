<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_id',
        'name',
        'description',
        'category',
        'price',
        'stock_quantity',
        'image_url'
    ];

    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}

