<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery_address extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name', 'ship',
        'address',
        'phone_number', 'idProvince', 'idDistrict', 'idWard'
    ];
    public function province()
    {
        return $this->belongsTo(Province::class, 'idProvince');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'idDistrict');
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class, 'idWard');
    }
}
