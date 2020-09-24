<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model {

    protected $table = 'request';
    protected $fillable = ['provider_id', 'operator_id', 'address', 'category_id', 'customer', 'sum', 'products', 'mode', 'phone_number'];
    protected $with = ['category', 'user'];

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'provider_id', 'id');
    }
}