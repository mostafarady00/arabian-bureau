<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Approval extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['approvals', 'image'];

    public $translatable = ['approvals'];
}
