<?php

namespace App\Http\Controllers;

use App\Models\Investor;
use App\Services\CardinalAlphaApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InvestorController extends Controller
{
    protected CardinalAlphaApiService $apiService;

    public function __construct(CardinalAlphaApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * Display a listing of investors
     */
    public function index()
    {
        $investors = Investor::orderBy('created_at', 'desc')->paginate(15);
        return view('investor.index', compact('investors'));
    }

    /**
     * Show the form for creating a new investor
     */
    public function create()
    {
        return view('investor.create');
    }

    /**
     * Store a newly created investor in database and push to API
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contact_number' => 'required|string|max:255',
        ]);

        try {
            // Push to API first
            $apiResponse = $this->apiService->createInvestor($validated);

            // Store in local database with API ID
            $investor = Investor::create([
                'api_id' => $apiResponse['id'],
                'name' => $validated['name'],
                'email' => $validated['email'],
                'contact_number' => $validated['contact_number'],
            ]);

            return redirect()
                ->route('investors.index')
                ->with('success', 'Investor created successfully and synced to API!');

        } catch (\Exception $e) {
            Log::error('Failed to create investor', [
                'error' => $e->getMessage(),
                'data' => $validated,
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to create investor: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified investor
     */
    public function edit(Investor $investor)
    {
        return view('investor.edit', compact('investor'));
    }

    /**
     * Update the specified investor in database and push to API
     */
    public function update(Request $request, Investor $investor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contact_number' => 'required|string|max:255',
        ]);

        try {
            // Push to API first (if we have an API ID)
            if ($investor->api_id) {
                $apiResponse = $this->apiService->updateInvestor($investor->api_id, $validated);
            }

            // Update local database
            $investor->update($validated);

            return redirect()
                ->route('investors.index')
                ->with('success', 'Investor updated successfully and synced to API!');

        } catch (\Exception $e) {
            Log::error('Failed to update investor', [
                'error' => $e->getMessage(),
                'investor_id' => $investor->id,
                'data' => $validated,
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to update investor: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified investor
     */
    public function show(Investor $investor)
    {
        $investor->load('investments.fund');
        return view('investor.show', compact('investor'));
    }

    /**
     * Remove the specified investor from storage
     */
    public function destroy(Investor $investor)
    {
        try {
            $investor->delete();

            return redirect()
                ->route('investors.index')
                ->with('success', 'Investor deleted successfully!');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Failed to delete investor: ' . $e->getMessage());
        }
    }
}
