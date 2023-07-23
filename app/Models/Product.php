<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $keyType = 'string';

    protected $fillable = [
        'id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function supllier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
