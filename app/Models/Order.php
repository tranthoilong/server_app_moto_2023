<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public static function generateId()
    {
        $date = date('Ymd'); // Get the current date in the format YYYYMMDD
        $time = round(microtime(true) * 1000); // Get the current time in milliseconds

        return $date . $time;
    }
    protected $fillable = [
        'id',
        'customer_id',
        'user_id',
        'status',
        'total_price',
        'address',
        'name',
        'payment',
        'note',
        'ship',
        'booking_date',
        'delivery_date',
        'total_products',
        'sub_total',
        'vat',
        'discount_code',
        'discount', 'phone',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function order_details()
    {
        return $this->hasMany(Order_details::class);
    }

    public function product()
    {
        return $this->hasMany(Order_details::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
