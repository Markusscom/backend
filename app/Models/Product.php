<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['website_id', 'name', 'description', 'price', 'stock'];

    public function website() {
        return $this->belongsTo(Website::class);
    }
}
