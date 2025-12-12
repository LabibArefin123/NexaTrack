<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'source',
        'link',
        'name',
        'company_name',
        'email',
        'phone',
        'address',
        'area',
        'post_code',
        'city',
        'country',
        'note',
    ];
}
