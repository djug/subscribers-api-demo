<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = ['email', 'name', 'state', 'user_id'];

    protected $hidden = ['id', 'user_id'];
}
