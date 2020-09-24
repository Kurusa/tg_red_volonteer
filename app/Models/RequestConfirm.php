<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestConfirm extends Model {

    protected $table = 'request_confirm';
    protected $fillable = ['request_id', 'file_id'];

    public function request()
    {
        return $this->belongsTo(Request::class, 'id', 'request_id');
    }
}