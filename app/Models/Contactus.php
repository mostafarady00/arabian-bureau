<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Contactus extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['address', 'tel', 'email', 'lat', 'lng', 'type'];

    // الحقول القابلة للترجمة
    public $translatable = ['address'];
}
