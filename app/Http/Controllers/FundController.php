<?php

namespace App\Http\Controllers;

use App\Models\Fund;

class FundController extends Controller
{
    /**
     * Display a listing of funds.
     */
    public function index()
    {
        $funds = Fund::withCount('investments')
            ->orderBy('name')
            ->paginate(15);

        return view('fund.index', compact('funds'));
    }

    /**
     * Display the specified fund.
     */
    public function show(Fund $fund)
    {
        $fund->load(['investments.investor']);

        return view('fund.show', compact('fund'));
    }
}
