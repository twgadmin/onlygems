<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
| Middleware options can be located in `app/Http/Kernel.php`
|
*/

// Homepage Route
Route::group(['middleware' => ['web', 'checkblocked']], function () {
    Route::get('/', function(){
            return view('auth.login');
        });
        Route::get('/testing', 'App\Http\Controllers\WelcomeController@index')->name('add-card-details');
        Route::post('/add-test-card-data', 'App\Http\Controllers\WelcomeController@add_test_card_data')->name('add-test-card-data');
        Route::get('/test-url/{id}', 'App\Http\Controllers\WelcomeController@testing_url')->name('test-url');

});

// Authentication Routes
Auth::routes();

// Public Routes
Route::group(['middleware' => ['web', 'activity', 'checkblocked']], function () {

    // Activation Routes
    Route::get('/activate', ['as' => 'activate', 'uses' => 'App\Http\Controllers\Auth\ActivateController@initial']);

    Route::get('/activate/{token}', ['as' => 'authenticated.activate', 'uses' => 'App\Http\Controllers\Auth\ActivateController@activate']);
    Route::get('/activation', ['as' => 'authenticated.activation-resend', 'uses' => 'App\Http\Controllers\Auth\ActivateController@resend']);
    Route::get('/exceeded', ['as' => 'exceeded', 'uses' => 'App\Http\Controllers\Auth\ActivateController@exceeded']);

    // Socialite Register Routes
    Route::get('/social/redirect/{provider}', ['as' => 'social.redirect', 'uses' => 'App\Http\Controllers\Auth\SocialController@getSocialRedirect']);
    Route::get('/social/handle/{provider}', ['as' => 'social.handle', 'uses' => 'App\Http\Controllers\Auth\SocialController@getSocialHandle']);

    // Route to for user to reactivate their user deleted account.
    Route::get('/re-activate/{token}', ['as' => 'user.reactivate', 'uses' => 'App\Http\Controllers\RestoreUserController@userReActivate']);
});

// Registered and Activated User Routes
Route::group(['middleware' => ['auth', 'activated', 'activity', 'checkblocked']], function () {

    // Activation Routes
    Route::get('/activation-required', ['uses' => 'App\Http\Controllers\Auth\ActivateController@activationRequired'])->name('activation-required');
    Route::get('/logout', ['uses' => 'App\Http\Controllers\Auth\LoginController@logout'])->name('logout');
});

// Registered and Activated User Routes
Route::group(['middleware' => ['auth', 'activated', 'activity', 'twostep', 'checkblocked']], function () {

    //  Homepage Route - Redirect based on user role is in controller.
    Route::get('/home', ['as' => 'public.home',   'uses' => 'App\Http\Controllers\UserController@index']);

    // Show users profile - viewable by other users.
    Route::get('profile/{username}', [
        'as'   => '{username}',
        'uses' => 'App\Http\Controllers\ProfilesController@show',
    ]);
});

// Registered, activated, and is current user routes.
Route::group(['middleware' => ['auth', 'activated', 'currentUser', 'activity', 'twostep', 'checkblocked']], function () {

    // User Profile and Account Routes
    Route::resource(
        'profile',
        \App\Http\Controllers\ProfilesController::class,
        [
            'only' => [
                'show',
                'edit',
                'update',
                'create',
            ],
        ]
    );
    Route::put('profile/{username}/updateUserAccount', [
        'as'   => '{username}',
        'uses' => 'App\Http\Controllers\ProfilesController@updateUserAccount',
    ]);
    Route::put('profile/{username}/updateUserPassword', [
        'as'   => '{username}',
        'uses' => 'App\Http\Controllers\ProfilesController@updateUserPassword',
    ]);
    Route::delete('profile/{username}/deleteUserAccount', [
        'as'   => '{username}',
        'uses' => 'App\Http\Controllers\ProfilesController@deleteUserAccount',
    ]);

    // Route to show user avatar
    Route::get('images/profile/{id}/avatar/{image}', [
        'uses' => 'App\Http\Controllers\ProfilesController@userProfileAvatar',
    ]);

    // Route to upload user avatar.
    Route::post('avatar/upload', ['as' => 'avatar.upload', 'uses' => 'App\Http\Controllers\ProfilesController@upload']);
});

// Registered, activated, and is admin routes.
Route::group(['middleware' => ['auth', 'activated', 'role:admin', 'activity', 'twostep', 'checkblocked']], function () {
    Route::resource('/users/deleted', \App\Http\Controllers\SoftDeletesController::class, [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ]);

    Route::resource('users', \App\Http\Controllers\UsersManagementController::class, [
        'names' => [
            'index'   => 'users',
            'destroy' => 'user.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);
    Route::post('search-users', 'App\Http\Controllers\UsersManagementController@search')->name('search-users');

    Route::post('users-list', 'App\Http\Controllers\UsersManagementController@list')->name('users-list');

    Route::resource('themes', \App\Http\Controllers\ThemesManagementController::class, [
        'names' => [
            'index'   => 'themes',
            'destroy' => 'themes.destroy',
        ],
    ]);
});

    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
    Route::get('routes', 'App\Http\Controllers\AdminDetailsController@listRoutes');
    Route::get('active-users', 'App\Http\Controllers\AdminDetailsController@activeUsers');


    // Route to upload user avatar.
    Route::post('/users/avatar/upload/{id}', ['as' => 'users.avatar.upload', 'uses' => 'App\Http\Controllers\UsersManagementController@upload']);

    Route::group(['middleware' => ['auth', 'activated', 'role:subadmin|admin', 'activity', 'twostep', 'checkblocked']], function () {
    // card hedger routes

    Route::resource('cardhedger', \App\Http\Controllers\CardhedgerController::class, [
        'names' => [
            'index'   => 'cardhedger',
            'destroy' => 'cardhedger.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);

    Route::get('cardhedger-list', 'App\Http\Controllers\CardhedgerController@index')->name('cardhedger-list');
    Route::get('add-new-card', 'App\Http\Controllers\CardhedgerController@create')->name('add-new-card');
    Route::post('fetch-cardhedger-list', 'App\Http\Controllers\CardhedgerController@list')->name('fetch-cardhedger-list');
    Route::get('add-card', 'App\Http\Controllers\CardController@create')->name('add-card');

    Route::post('fetch-cards-list', 'App\Http\Controllers\CardController@list')->name('fetch-cards-list');

    Route::post('/save-card-data-vendhq', 'App\Http\Controllers\CardController@save_card_data_vendhq')->name('save-card-data-vendhq');
    Route::post('/save-card-details', 'App\Http\Controllers\CardController@save_card_details')->name('save-card-details');

    Route::get('get-serial-number', 'App\Http\Controllers\CardController@getSerialNumber')->name('get-serial-number');

    Route::get('/tasks', 'App\Http\Controllers\CardController@exportCsv');
    // Route::get('/records', 'App\Http\Controllers\CardController@exportCsv');

    Route::post('/fetch-cardhedger-cards-from-api', 'App\Http\Controllers\CardController@getCardsFromApi');
    Route::get('/get-price-list', 'App\Http\Controllers\CardController@getPriceList');
    

	
    Route::resource('cards', \App\Http\Controllers\CardController::class, [
        'names' => [
            'index' => 'cards-inventory',
            'destroy' => 'cards.destroy'
        ],
        'except' => [
            'deleted'
        ],
    ]);

    Route::get('get-card-details', 'App\Http\Controllers\CardController@getCardDetails')->name('get-card-details');
    Route::get('cards-inventory', 'App\Http\Controllers\CardController@index')->name('cards-inventory');
    Route::post('sheets/importSheet', ['App\Http\Controllers\CardController@importSheet'])->name('importSheet');
    Route::get('getSheet', 'App\Http\Controllers\CardController@getSheet')->name('getSheet');
    Route::get('getSheetSecMod', 'App\Http\Controllers\CardController@getSheetForCardID')->name('getSheetSecMod');
    Route::resource('sheets', CardController::class);
});



Route::redirect('/php', '/phpinfo', 301);
Route::post('processed-order', 'App\Http\Controllers\HomeController@processedOrder')->name('processed.order');
Route::post('upload-sheet-data', 'App\Http\Controllers\HomeController@importSheetFromApi')->name('upload.sheet.data');
Route::post('upload-posted-data', 'App\Http\Controllers\HomeController@createFileFromWebPost')->name('upload.posted.data');
Route::post('verify-phone-number', 'App\Http\Controllers\HomeController@verifyPhoneNumber')->name('verify.phone.number');
Route::post('confirm-sheet-data', 'App\Http\Controllers\HomeController@confirmSheetData')->name('confirm.sheet.data');


Route::get('order-product-for-minted/{orderid}/{productid}/{status}/status', 'App\Http\Controllers\HomeController@updateOrdersProductsMinted')->name('order.product.for.minted');
Route::get('order-product-for-senttowallet/{orderid}/{productid}/{status}/status', 'App\Http\Controllers\HomeController@updateOrdersProductsSentToWallet')->name('order.product.for.senttowallet');

Route::get('order-product/{orderid}/{productid}/{status}/status', 'App\Http\Controllers\HomeController@updateOrdersProducts')->name('order.product.status');
Route::get('order-status/{orderid}/{status}/status', 'App\Http\Controllers\HomeController@updateOrderStatus')->name('order.status');
Route::get('send-verification-code', 'App\Http\Controllers\HomeController@sendSmsAgain')->name('send.verification.code');
Route::get('send-verification-email', 'App\Http\Controllers\HomeController@sendEmailCodeAgain')->name('send.verification.email');
Route::get('order/{orderid}/details', 'App\Http\Controllers\HomeController@ordersDetails')->name('order.details');
Route::get('order-list', 'App\Http\Controllers\HomeController@ordersListing')->name('order.list');
Route::get('export-order-pdf','App\Http\Controllers\HomeController@exportOrderPDF')->name('export.order.pdf');
Route::get('/vault-submission', 'App\Http\Controllers\HomeController@vaultSubmission')->name('vault.submission');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Auth::routes();
