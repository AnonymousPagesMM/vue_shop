<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name','user_id','category_id','description','information','image','qty'];

    public function category_name()
    {
        return $this->hasOne(Category::class,'id','category_id');
    }
}
