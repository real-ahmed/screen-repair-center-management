<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;
    protected $fillable = ['id','name','category_id'];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function screenComponents(){
        return $this->hasMany(ScreenComponent::class);
    }



    public function unlinked(){
        return !(
            $this->screenComponents()->exists()
        );
    }
}
