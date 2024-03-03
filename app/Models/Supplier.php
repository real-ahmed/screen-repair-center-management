<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $fillable = ['id','name','phone'];
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function unlinked(){
        return $this->purchaseitem()->doesntExist();
    }
}
