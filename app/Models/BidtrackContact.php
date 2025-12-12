<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BidtrackContact extends Model
{
    protected $connection = 'bidtrack';
    protected $table = 'contacts'; // adjust if needed

    protected $fillable = ['name', 'email', 'phone', 'company_name', 'address', 'note'];
}
