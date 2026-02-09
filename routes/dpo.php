<?php


use App\Http\Controllers\Backend\ProxyController;
use App\Http\Controllers\Callbacks\DPOCallbackController;
use Illuminate\Support\Facades\Route;

Route::get('/proxy-check', [ProxyController::class, 'check'])->name('dpo.proxy');
	Route::get('/check', [DPOCallbackController::class, 'verifyToken'])->name('dpo.verify_token');
