<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Company_profile extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'main_image',
        'icon',
        'organization_image',
        'company_profile',
        'business_interest',
        'organization_subdesc',
        'organization_desc',
    ];

    // تحديد الحقول التي يمكن ترجمتها
    public $translatable = [
        'company_profile',
        'business_interest',
        'organization_subdesc',
        'organization_desc',
    ];
}
