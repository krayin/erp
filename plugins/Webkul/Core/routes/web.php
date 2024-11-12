<?php

use Illuminate\Support\Facades\Route;
use Webkul\Core\Livewire\AcceptInvitation;

Route::middleware(['web'])->group(function () {
    Route::middleware('signed')
        ->get('invitation/{invitation}/accept', AcceptInvitation::class)
        ->name('core.invitation.accept');
});
