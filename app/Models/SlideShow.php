<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlideShow extends Model
{
    use HasFactory;
    protected $fillable = ['custom_number',
    'title',
    'image',
    'description',
    'user_id',
    'link'];
}
