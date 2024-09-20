<?php

use App\Http\Controllers\API\AppSettingsController;
use App\Http\Controllers\API\UserFirebaseController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CancellationReasonController;
use App\Http\Controllers\API\AccountManagementController;
use App\Http\Controllers\API\TagController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\BannerController;
use App\Http\Controllers\API\FavouriteController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\VendorController;
use App\Http\Controllers\API\PartnerController;
use App\Http\Controllers\API\VendorTypeController;
use App\Http\Controllers\API\CouponController;
use App\Http\Controllers\API\SearchDataController;
use App\Http\Controllers\API\SearchController;
use App\Http\Controllers\API\DeliveryAddressController;
use App\Http\Controllers\API\PaymentMethodController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\TrackOrderController;
use App\Http\Controllers\API\PackageOrderController;
use App\Http\Controllers\API\RegularOrderController;
use App\Http\Controllers\API\PackageTypeController;
use App\Http\Controllers\API\ChatNotificationController;
use App\Http\Controllers\API\DriverTypeController;
use App\Http\Controllers\API\RatingController;
use App\Http\Controllers\API\EarningController;
use App\Http\Controllers\API\ExternalRedirectController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\WalletController;
use App\Http\Controllers\API\OrderPaymentCallbackController;
use App\Http\Controllers\API\WebhookPaymentCallbackController;
use App\Http\Controllers\API\OTPController;
use App\Http\Controllers\API\SocialLoginController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\ProductReviewController;
use App\Http\Controllers\API\ProductReviewSummaryController;
use App\Http\Controllers\API\VendorPackageTypePricingController;
use App\Http\Controllers\API\VendorServiceController;
use App\Http\Controllers\API\VehicleTypeController;
use App\Http\Controllers\API\TaxiOrderController;
use App\Http\Controllers\API\TaxiDriverOrderController;
use App\Http\Controllers\API\PaymentAccountController;
use App\Http\Controllers\API\PayoutController;
use App\Http\Controllers\API\WalletTransferController;
use App\Http\Controllers\API\LoyaltyPointController;
use App\Http\Controllers\API\GeocoderController;
use App\Http\Controllers\API\ProductMiscController;
use App\Http\Controllers\API\FlashSaleController;
use App\Http\Controllers\API\OnboardingController;
use App\Http\Controllers\API\FaqController;
use App\Http\Controllers\API\SignedMediaController;
use App\Http\Controllers\API\MyVendorController;
use App\Http\Controllers\API\VendorReportController;
use App\Http\Controllers\API\DriverReportController;
use App\Http\Controllers\API\DocumentRequestController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//CRON Job
Route::get('/cron/job', function (Request $request) {
    //
    $appKey  = env("CRON_JOB_KEY", "");
    $urlAppKey = str_ireplace(" ", "+", $request->key);
    //
    if ($appKey != $urlAppKey) {
        return response()->json([
            "message" => "Unauthorized",
        ], 401);
    }

    $artisan = \Artisan::call("schedule:run");
    $output = \Artisan::output();
    return response()->json([
        "message" => "schedule runed",
        "output" => $output
    ]);
})->name('cron.job');

//App settings
Route::get('/app/settings', [AppSettingsController::class, 'index']);
Route::get('/app/onboarding', [OnboardingController::class, 'index']);
Route::get('/app/faqs', [FaqController::class, 'index']);
Route::get('/cancellation/reasons', [CancellationReasonController::class, 'index']);

// Auth
Route::group(['middleware' => ['throttle:otp']], function () {
    //otps
    Route::post('otp/send', [OTPController::class, 'sendOTP'])->name('otp.send');
    Route::post('otp/verify', [OTPController::class, 'verifyOTP'])->name('otp.verify');
    Route::post('otp/firebase/verify', [OTPController::class, 'verifyFirebaseToken'])->name('otp.firebase.verify');
});

Route::post('login', [AuthController::class, 'login']);
Route::post('vendor/register', [PartnerController::class, 'vendor']);
Route::post('driver/register', [PartnerController::class, 'driver']);
Route::post('login/qrcode', [AuthController::class, 'qrlogin']);
Route::post('social/login', [SocialLoginController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::get('verify/phone', [AuthController::class, 'verifyPhoneAccount']);
Route::post('password/reset/init', [AuthController::class, 'passwordReset']);
// Route::get('password/update/{code}/{email}', PasswordResetLivewire::class)->name('password.reset.link');

Route::get('tags', [TagController::class, "index"]);
Route::get('categories', [CategoryController::class, "index"]);
Route::get('banners', [BannerController::class, "index"]);
Route::apiResource('products', ProductController::class);
Route::get('product/review/summary', [ProductReviewSummaryController::class, 'index']);
Route::get('product/frequent', [ProductMiscController::class, 'frequent']);
Route::apiResource('services', ServiceController::class);
Route::get('service/durations', [ServiceController::class, 'durations']);
Route::apiResource('vendors', VendorController::class);
Route::get('vendor/reviews', [ReviewController::class, 'index']);
Route::get('product/reviews', [ProductReviewController::class, 'index']);
Route::apiResource('vendor/types', VendorTypeController::class);
Route::get('coupons/{code}', [CouponController::class, 'show']);
Route::get('coupons', [CouponController::class, 'index']);
Route::get('coupons/details/{id}', [CouponController::class, 'details']);
Route::get('search', [SearchController::class, 'index']);
Route::get('search/data', [SearchDataController::class, 'index']);
Route::get('flash/sales', [FlashSaleController::class, 'index']);

//package delivery
Route::get('package/types', [PackageTypeController::class, 'index']);
Route::get('partner/vehicle/types', [PartnerController::class, 'vehicleTypes']);
Route::get('partner/car/makes', [PartnerController::class, 'carMakes']);
Route::get('partner/car/models', [PartnerController::class, 'carModels']);
//
Route::post('order/payment/callback', [OrderPaymentCallbackController::class, 'order'])->name('api.payment.callback');
Route::post('wallet/topup/callback', [OrderPaymentCallbackController::class, 'wallet'])->name('api.wallet.topup.callback');
Route::post('subscription/callback', [OrderPaymentCallbackController::class, 'subscription'])->name('api.subscription.callback');
Route::any('external/payment/callback/{hash}', [WebhookPaymentCallbackController::class, 'index'])->name('api.payment.webhook');
Route::apiResource('payment/methods', PaymentMethodController::class)->only('index');

//geocoding
Route::get('geocoder/{type}', [GeocoderController::class, 'index']);
Route::get('geocoder/2/reserve', [GeocoderController::class, 'newReverse']);
Route::get('geocoder/place/details', [GeocoderController::class, 'reverseDetails']);
Route::get('/download/digital/files/{id}', [SignedMediaController::class, 'download'])->name('digital.download');
//Server run external apis
Route::apiResource('external/redirect', ExternalRedirectController::class)->only('index');
Route::get('external/web/redirect', [ExternalRedirectController::class, 'webRedirect']);




Route::group(['middleware' => ['auth:sanctum', "user.active.check"]], function () {

    Route::apiResource('favourites', FavouriteController::class);
    Route::post('device/token/sync', [UserFirebaseController::class, 'syncTokens']);
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('my/profile', [UserController::class, 'myProfile']);
    Route::put('profile/update', [AuthController::class, 'profileUpdate']);
    Route::put('profile/password/update', [AuthController::class, 'changePassword']);
    Route::delete('account/delete', [AccountManagementController::class, 'delete']);
    Route::apiResource('delivery/addresses', DeliveryAddressController::class);
    Route::apiResource('orders', OrderController::class)->only('index', 'show', 'update');
    Route::middleware('throttle.order.api')->group(function () {
        Route::post('orders', [OrderController::class, 'store']);
    });

    Route::post('/track/order', [TrackOrderController::class, "track"]);
    Route::apiResource('rating', RatingController::class)->only('store');
    Route::post('product/reviews', [ProductReviewController::class, 'store']);
    //package delivery
    Route::post('package/order/vendors', [PackageOrderController::class, 'fetchVendors']);
    Route::get('package/order/summary', [PackageOrderController::class, 'summary']);
    Route::get('general/order/delivery/fee/summary', [RegularOrderController::class, 'deliveryFeeSummary']);
    Route::post('general/order/summary', [RegularOrderController::class, 'summary']);
    Route::post('service/order/summary', [RegularOrderController::class, 'serviceSummary']);
    Route::post('package/order/stop/verify/{id}', [PackageOrderController::class, 'verifyOrderStop']);
    //
    Route::post('chat/notification', [ChatNotificationController::class, 'send']);

    //earning
    Route::get('earning/user', [EarningController::class, 'user']);
    Route::get('earning/vendor', [EarningController::class, 'vendor']);
    Route::get('users', [UserController::class, 'index']);
    Route::get('vendor/{id}/details', [VendorController::class, 'fullDeatils']);

    //wallets
    Route::get('wallet/balance', [WalletController::class, 'index']);
    Route::post('wallet/topup', [WalletController::class, 'topup']);
    Route::get('wallet/transactions', [WalletController::class, 'transactions']);
    Route::post('wallet/transfer', [WalletController::class, 'transferBalance']);

    //taxi booking
    Route::get('vehicle/types', [VehicleTypeController::class, 'index']);
    Route::get('vehicle/types/pricing', [VehicleTypeController::class, 'calculateFee']);
    Route::post('taxi/book/order', [TaxiOrderController::class, 'book']);
    Route::get('taxi/current/order', [TaxiOrderController::class, 'current']);
    Route::get('taxi/rateable/order', [TaxiOrderController::class, 'currentRateable']);
    Route::get('taxi/order/cancel/{id}', [TaxiOrderController::class, 'cancelOrder']);
    Route::get('taxi/driver/info/{id}', [TaxiOrderController::class, 'driverInfo']);
    Route::get('taxi/location/available', [TaxiOrderController::class, 'location_available']);
    Route::get('taxi/location/history', [TaxiOrderController::class, 'location_history']);
    // driver section
    Route::group(['middleware' => ['role:driver']], function () {
        Route::post('taxi/order/asignment/reject', [TaxiDriverOrderController::class, 'driverRejectAssignment']);
        Route::post('taxi/order/asignment/accept', [TaxiDriverOrderController::class, 'driverAcceptAssignment']);
    });

    //Payments
    Route::apiResource('payment/accounts', PaymentAccountController::class);
    Route::apiResource('payouts/request', PayoutController::class)->only('store');
    //Wallet transfer
    Route::get('wallet/my/address', [WalletTransferController::class, 'walletAddress']);
    Route::get('wallet/address/search', [WalletTransferController::class, 'walletAddressSearch']);
    Route::post('wallet/address/transfer', [WalletTransferController::class, 'walletTransfer']);
    //loyalty point
    Route::get('loyalty/point/my', [LoyaltyPointController::class, 'current']);
    Route::get('loyalty/point/my/report', [LoyaltyPointController::class, 'report']);
    Route::post('loyalty/point/my/withdraw', [LoyaltyPointController::class, 'withdraw']);

    //
    Route::group(['middleware' => ['role:manager']], function () {

        Route::post('availability/vendor/{id}', [VendorController::class, 'toggleVendorAvailablity']);
        Route::apiResource('/vendor/package/pricing', VendorPackageTypePricingController::class);
        //separate the my services into get, post, put, delete
        Route::get('/my/services', [VendorServiceController::class, 'index']);
        Route::post('/my/services', [VendorServiceController::class, 'store']);
        Route::put('/my/services/{id}', [VendorServiceController::class, 'update']);
        Route::delete('/my/services/{id}', [VendorServiceController::class, 'destroy']);

        //
        Route::get('/my/vendors', [MyVendorController::class, 'index']);
        Route::post('/switch/vendor', [MyVendorController::class, 'switchVendor']);
        Route::get('/my/vendor/sales/report', [VendorReportController::class, 'sales']);
        Route::get('/my/vendor/earnings/report', [VendorReportController::class, 'earnings']);

        //document submission
        Route::post('/my/vendor/document/request/submission', [DocumentRequestController::class, 'vendor']);
    });


    //driver
    Route::group(['middleware' => ['role:driver']], function () {
        Route::post('driver/type/switch', [DriverTypeController::class, 'switchType']);
        Route::post('driver/vehicle/register', [DriverTypeController::class, 'registerVehicle']);
        Route::get('driver/vehicles', [DriverTypeController::class, 'vehicles']);
        Route::post('driver/vehicle/{id}/activate', [DriverTypeController::class, 'activateVehicle']);
        // Route::post('driver/order/accept', [DriverTypeController::class, 'activateVehicle']);
        // Route::post('driver/order/reject', [DriverTypeController::class, 'activateVehicle']);
        //document submission
        Route::post('/driver/document/request/submission', [DocumentRequestController::class, 'driver']);
        //summary report
        Route::get('/driver/payouts/report', [DriverReportController::class, 'payouts']);
        Route::get('/driver/earnings/report', [DriverReportController::class, 'earnings']);
    });
});