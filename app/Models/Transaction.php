<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'code_transaction',
        'ref_buyer',
        'address'
    ];

    public function products(){
        return $this->belongsToMany(Product::class)->withPivot('price', 'qty');;
    }
}
