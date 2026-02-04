<?php

use App\Http\Controllers\FundController;
use App\Http\Controllers\GraphController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\InvestorController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/instructions', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('investors', InvestorController::class);

Route::get("funds", [FundController::class, 'index'])->name('funds.index');
Route::get("funds/{fund}", [FundController::class, 'show'])->name('funds.show');

Route::get("investments", [InvestmentController::class, 'index'])->name('investments.index');
Route::get("investments/{investment}", [InvestmentController::class, 'show'])->name('investments.show');

Route::get("graph", [GraphController::class, 'index'])->name('graph.index');
Route::get('/exam', function () {
    return view('exam.index');
})->name('exam.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
