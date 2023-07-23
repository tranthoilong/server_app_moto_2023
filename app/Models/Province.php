<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;
    protected $primaryKey = 'province_id';
    public $timestamps = false;

    public function districts()
    {
        return $this->hasMany(District::class, 'province_id');
    }
}
class District extends Model
{
    protected $primaryKey = 'district_id';
    public $timestamps = false;

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function wards()
    {
        return $this->hasMany(Ward::class, 'district_id');
    }
}

class Ward extends Model
{
    protected $primaryKey = 'wards_id';
    public $timestamps = false;

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
}
