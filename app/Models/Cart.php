<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'specification_id',
        'quantity'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function specification()
    {
        return $this->belongsTo(Specification::class);
    }

    public function scopeUserCart($query)
    {
        return $query->where('user_id', Auth::user()->id);
    }

}
