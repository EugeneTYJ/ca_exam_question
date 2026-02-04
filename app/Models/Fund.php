<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fund extends Model
{
    protected $fillable = [
        'api_id',
        'name',
    ];

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }
}
