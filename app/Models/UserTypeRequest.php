<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTypeRequest extends Model {

    protected $table = 'user_type_request';
    protected $fillable = ['chat_id'];

}