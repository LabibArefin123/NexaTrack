<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimetrackContact extends Model
{
    protected $connection = 'timetrack';
    protected $table = 'contacts'; // adjust if needed

    protected $fillable = ['name', 'email', 'phone', 'company_name', 'address', 'note'];
}
