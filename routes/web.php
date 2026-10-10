<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DueController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettlementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('groups.index')
        : view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Join grup via kode undangan
    Route::get('/join', fn () => view('groups.join'))->name('groups.join');
    Route::post('/join', [GroupController::class, 'join'])->name('groups.join.post');

    // Grup
    Route::resource('groups', GroupController::class);
    Route::get('/groups/{group}/invite', [GroupController::class, 'inviteLink'])->name('groups.invite');
    Route::post('/groups/{group}/invite/regenerate', [GroupController::class, 'regenerateInvite'])->name('groups.invite.regenerate');
    Route::post('/groups/{group}/members/guest', [GroupController::class, 'addGuest'])->name('groups.members.guest');
    Route::delete('/groups/{group}/members/{member}', [GroupController::class, 'removeMember'])->name('groups.members.remove');

    // Pengeluaran (nested di bawah grup)
    Route::prefix('groups/{group}')->name('groups.')->group(function () {
        Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
        Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
        Route::get('/expenses/{expense}', [ExpenseController::class, 'show'])->name('expenses.show');
        Route::get('/expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
        Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
        Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

        // Iuran
        Route::get('/dues', [DueController::class, 'index'])->name('dues.index');
        Route::get('/dues/create', [DueController::class, 'create'])->name('dues.create');
        Route::post('/dues', [DueController::class, 'store'])->name('dues.store');
        Route::get('/dues/{due}', [DueController::class, 'show'])->name('dues.show');
        Route::delete('/dues/{due}', [DueController::class, 'destroy'])->name('dues.destroy');
        Route::post('/dues/payments/{payment}/confirm', [DueController::class, 'confirmPayment'])->name('dues.payments.confirm');
        Route::post('/dues/payments/{payment}/proof', [DueController::class, 'submitProof'])->name('dues.payments.proof');

        // Pelunasan
        Route::get('/settlements', [SettlementController::class, 'index'])->name('settlements.index');
        Route::post('/settlements', [SettlementController::class, 'store'])->name('settlements.store');
        Route::post('/settlements/{settlement}/confirm', [SettlementController::class, 'confirm'])->name('settlements.confirm');
        Route::delete('/settlements/{settlement}', [SettlementController::class, 'destroy'])->name('settlements.destroy');
    });
});

require __DIR__.'/auth.php';
