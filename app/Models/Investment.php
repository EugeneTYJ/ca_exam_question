<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    protected $fillable = [
        'api_id',
        'uid',
        'investor_id',
        'fund_id',
        'start_date',
        'capital_amount',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'capital_amount' => 'decimal:2',
    ];

    public function investor()
    {
        return $this->belongsTo(Investor::class);
    }

    public function fund()
    {
        return $this->belongsTo(Fund::class);
    }
}
