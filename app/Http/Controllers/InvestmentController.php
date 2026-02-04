<?php

namespace App\Http\Controllers;

use App\Models\Investment;

class InvestmentController extends Controller
{
    /**
     * Display a listing of investments.
     */
    public function index()
    {
        $investments = Investment::with(['investor', 'fund'])
            ->orderBy('start_date', 'desc')
            ->paginate(15);

        return view('investment.index', compact('investments'));
    }

    /**
     * Display the specified investment.
     */
    public function show(Investment $investment)
    {
        $investment->load(['investor', 'fund']);

        return view('investment.show', compact('investment'));
    }
}
