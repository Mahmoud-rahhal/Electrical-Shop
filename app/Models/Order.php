<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'total_amount',
        'item_count',
        'payment_status',
        'payment_method',
        'first_name',
        'last_name',
        'address',
        'city',
        'country',
        'post_code',
        'phone_number',
        'notes',
    ];

    protected $casts = [
        'payment_status' => 'boolean',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Relationship: Order belongs to a User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Order has many OrderItems (assuming you have an order_items table)
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

}
