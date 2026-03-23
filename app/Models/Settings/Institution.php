<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'logo',
    'background',
    'name',
    'address',
    'email',
    'website',
    'appUrl',
    'contact',
    'created_by',
    'updated_by',
])]
class Institution extends Model
{
    //
}
