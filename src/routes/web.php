<?php

use Illuminate\Support\Facades\Route;

Route::get('/lapi-payment/postback', function (\Illuminate\Http\Request $request) {

    (new \Ernandesrs\LapiPayment\Services\Payments\LapiPay())->postback($request);

})->name('lapi-payment.postback');