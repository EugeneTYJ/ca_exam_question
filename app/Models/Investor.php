<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Investor extends Model
{
    protected $fillable = [
        'api_id',
        'name',
        'email',
        'contact_number',
    ];

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }
}
