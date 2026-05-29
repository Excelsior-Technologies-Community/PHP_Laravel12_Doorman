<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoormanController;

Route::get('/', [DoormanController::class, 'index']);

Route::get('/invites', [DoormanController::class, 'invitesPage']);
Route::get('/search-invites', [DoormanController::class, 'searchInvites']);
Route::delete('/invites/{id}', [DoormanController::class, 'deleteInvite']);
Route::post('/invites/bulk-delete', [DoormanController::class, 'bulkDeleteInvites']);
Route::get('/invites/export', [DoormanController::class, 'exportInvites']);
Route::get('/invites/{id}/details', [DoormanController::class, 'getInviteDetails']);

Route::get('/generate-single', [DoormanController::class, 'generateSingle']);
Route::get('/generate-multiple', [DoormanController::class, 'generateMultiple']);
Route::get('/generate-expiry', [DoormanController::class, 'generateExpiry']);
Route::get('/generate-email', [DoormanController::class, 'generateEmail']);
Route::get('/redeem', [DoormanController::class, 'redeemForm']);
Route::post('/redeem', [DoormanController::class, 'redeem']);