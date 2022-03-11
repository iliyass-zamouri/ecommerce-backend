<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'slug',
        'label',
        'description',
        'gender',
        'mark_id',
        'category_id',
    ];

    public function scopeAllInfo($query)
    {
        return $query->with('mark')->with('photos')->with('category')->with('specifications');
    }

    public function mark()
    {
        return $this->belongsTo(Mark::class);
    }
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
    public function specifications()
    {
        return $this->hasMany(Specification::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
