<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'stock',
        'available',
        'ref_seller',
        'ref_category',
        'price',
        'main_image',
        'description'
    ];

    public function seller()
    {
        return $this->belongsTo(User::class);
    }
    
    public function transaction(){
        return $this->belongsToMany(Transaction::class);
    }

}
