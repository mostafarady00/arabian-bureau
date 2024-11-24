<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class About extends Model
{
    use HasFactory, HasTranslations;
    protected $table = 'about';
    // تحديد الحقول القابلة للترجمة
    public $translatable = [
        'about',
        'icon_subdesc',
        'icon_desc'
    ];

    protected $fillable = [
        'image',
        'icon',
        'about',
        'icon_subdesc',
        'icon_desc',
    ];
}
