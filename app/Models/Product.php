<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'id'; // поле с уникальным индификатором
    protected $fillable = [	 // разрешенные поля для редактирования
        'id',
        'created_at',
        'updated_at',
        'title',
        'status',
        'meta',
    ];
}
