<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Orders;
use DateTimeInterface;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';
    protected $fillable = [
        'order_id'=> 'integer',
        'product_id' => 'integer',
        'quantity'=>'integer',
        'price',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'


    ];

    public function orders()
    {
        return $this->belongsTo(Orders::class);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
