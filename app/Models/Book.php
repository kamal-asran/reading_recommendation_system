<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'num_of_pages'
    ];

    public function readingIntervals()
    {
        return $this->hasMany(ReadingInterval::class);
    }
}
