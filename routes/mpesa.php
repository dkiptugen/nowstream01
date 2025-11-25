<?php
	
	
	use App\Http\Controllers\Callbacks\MpesaCallbackController;
	use Illuminate\Support\Facades\Route;
    Route::middleware(['cor'])->group( function () {
        Route::post('notify', [MpesaCallbackController::class, 'notify'])->name('mpesa.notify');
        Route::post('check_payment', [MpesaCallbackController::class, 'check_mpesa_payment'])->name('mpesa.check_payment');
        Route::any('validation', [MpesaCallbackController::class, 'validation'])->name('mpesa.validation');
        Route::any('confirmation', [MpesaCallbackController::class, 'confirmation'])->name('mpesa.confirmation');
        Route::any('b2b', [MpesaCallbackController::class, 'b2b'])->name('mpesa.b2b');
        Route::any('b2c', [MpesaCallbackController::class, 'b2c'])->name('mpesa.b2c');
        Route::any('account_balance', [MpesaCallbackController::class, 'account_balance'])->name('mpesa.account_balance');
        Route::any('reversal', [MpesaCallbackController::class, 'reversal'])->name('mpesa.reversal');
        Route::get('transaction_status', [MpesaCallbackController::class, 'transaction_status'])->name('mpesa.transaction_status');
        Route::get('stk_push_request/{subscription}', [MpesaCallbackController::class, 'stk_push_request'])->name('mpesa.stk_push_request');
        Route::get('stk_callback', [MpesaCallbackController::class, 'stk_callback'])->name('mpesa.stk_callback');
        Route::get('stk_push_query', [MpesaCallbackController::class, 'stk_push_query'])->name('mpesa.stk_push_query');
    });
	 