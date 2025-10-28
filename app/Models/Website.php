<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $fillable = ['name', 'url'];

    public function products() {
        return $this->hasMany(Product::class);
    }
}

