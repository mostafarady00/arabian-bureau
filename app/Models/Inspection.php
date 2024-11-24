<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Inspection extends Model
{
    use HasFactory, HasTranslations;

   

    protected $fillable = ['inspections', 'title', 'description', 'image'];

    public $translatable = ['inspections', 'title', 'description'];
}
