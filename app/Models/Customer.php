<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'software',
        'name',
        'email',
        'phone',
        'company_name',
        'address',
        'area',
        'city',
        'country',
        'post_code',
        'note',
        'source',
    ];
}
