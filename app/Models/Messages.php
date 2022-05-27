<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Messages extends Model
{
    use HasFactory;

    protected $table = 'messages';
    protected $fillable = [
        'id_send'=> 'integer',
        'id_take' => 'integer',
        'messaging'=>'integer',
        'image',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'


    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
