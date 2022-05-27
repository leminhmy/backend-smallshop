<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Shoes extends Model
{

    protected $table = 'shoes';

     protected $fillable = [
        'name',
        'sub_title',
        'price',
        'type_id',
        'description',
        'released',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'

    ];

    use HasFactory;
    public function shoes(){
        return $this->hasOne(ShoesType::class, 'id','type_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
