<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    public $timestamps = false;
    use HasFactory;
    protected $fillable = ['user_id','token','user_agent','id','payload','last_activity'];
}
