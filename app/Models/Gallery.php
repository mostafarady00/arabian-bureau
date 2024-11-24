<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Gallery extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['Gallery', 'image', 'description_image'];

    // الحقول القابلة للترجمة
    public $translatable = ['Gallery', 'description_image'];
}
