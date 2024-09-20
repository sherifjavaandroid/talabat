<?php

use App\Http\Controllers\API\AuthRedirectController;
use App\Http\Livewire\InAppSupportPageLivewire;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CMSPageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['web']], function () {


    //redirect api to authenticated web route
    Route::get('/auth/redirect', [AuthRedirectController::class, 'index'])->name('web.auth.redirect');

    // Pages
    Route::get('privacy/policy', function () {
        return view('layouts.includes.privacy');
    })->name('privacy');

    Route::get('pages/contact', function () {
        return view('layouts.includes.contact');
    })->name('contact');

    Route::get('pages/terms', function () {
        return view('layouts.includes.terms');
    })->name('terms');

    Route::get('pages/shipping/terms', function () {
        return view('layouts.includes.shipping-terms');
    })->name('shipping.terms');
    Route::get('pages/refund/terms', function () {
        return view('layouts.includes.refund-terms');
    })->name('refund.terms');
    Route::get('pages/cancel/terms', function () {
        return view('layouts.includes.cancel-terms');
    })->name('cancel.terms');


    Route::get('pages/payment/terms', function () {
        return view('layouts.includes.payment-terms');
    })->name('payment.terms');
    //
    Route::get('support/chat', InAppSupportPageLivewire::class)->name('support.chat');
    Route::get('cms/{slug}', [CMSPageController::class, 'index'])->name('cms.page');
});