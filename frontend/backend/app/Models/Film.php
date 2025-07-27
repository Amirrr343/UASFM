<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;

    // Field yang boleh diisi (fillable) melalui mass-assignment
    protected $fillable = [
     'title', 
     'description', 
     'genre', 
     'release_year'
    ];

    public $timestamps = false;
    // Jika ingin menggunakan timestamps, ubah ke true
}
