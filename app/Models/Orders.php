<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;
use DateTimeInterface;

class Orders extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $fillable = [
        'user_id'=> 'integer',
        'order_amount' => 'int',
        'phone' => 'integer',
        'address',
        'status',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'

    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

     public function details()
    {
        return $this->hasMany(OrderItem::class);
    }
}
