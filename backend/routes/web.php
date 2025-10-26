<?php

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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::group(['middleware' => ['web']], function () {

############################################### Front Routing start here ########################################
    Route::get('/base/uploder', 'BaseController@uploder');
    Route::post('/base/uploder', 'BaseController@uploder');
    Route::any('update-delivery-type', 'CartController@updateDeliveryType');


    Route::group(array('before' => 'guestfront'), function () {

        Route::any('get_orders', 'PrintorderController@getIndex');
        Route::any('orders_reply', 'PrintorderController@getReply');

        Route::any('add-to-cart', 'CartController@addCart');
        ### logout route ###
        Route::get('logout', array(
            'as' => 'logout',
            'uses' => 'LoginController@logout'
        ));

        ###### home route ######
        Route::get('/', 'HomeController@index');
        Route::any('setLatLong', 'HomeController@setLatLong');
        Route::get('cuisine/{slug}', 'HomeController@index');
        Route::get('restaurant-detail/{slug}', 'HomeController@restaurantDetail');

        ###### login route ######
        Route::any('login', array(
            'as' => 'login',
            'uses' => 'LoginController@login'
        ));

        Route::any('login-facebook', array(
            'as' => 'login-facebook',
            'uses' => 'LoginController@loginWithFacebook'
        ));
        Route::any('login-twitter', array(
            'as' => 'login-twitter',
            'uses' => 'LoginController@loginWithTwitter'
        ));
        Route::any('login-google', array(
            'as' => 'login-google',
            'uses' => 'LoginController@loginWithGoogle'
        ));

        Route::any('login/{validstring}', array(
            'as' => 'login',
            'uses' => 'LoginController@Login'
        ));

        ###### account regiter,forget password, change password, reset password route ######
        Route::get('signup', 'RegistrationController@getIndex');
        Route::get('signup/{type}', 'RegistrationController@getIndex');
        Route::post('registration', 'RegistrationController@postIndex');
        Route::get('send-verification-link/{validstring}', 'RegistrationController@send_verification_link');
        Route::get('send-verification-link', 'RegistrationController@send_verification_link');
        Route::any('forget_password', 'LoginController@forgetPassword');
        Route::any('send_password', 'LoginController@sendPassword');
        Route::get('reset_password/{validstring}', 'LoginController@resetPassword');
        Route::post('save_password', 'LoginController@resetPasswordSave');
        Route::get('account-verification/{validate_string}', 'RegistrationController@accountVerification');

        ############# cms page ##############

        ###### page ######
        Route::get('/pages/{slug}', 'HomeController@showCms');
        ###### faq  ######
        Route::any('faq', 'FaqController@index');
        ###### about us ######
        Route::get('about-us', 'HomeController@aboutUs');
        ###### contact us ######
        Route::any('contact-us', 'HomeController@contactUs');
    });

//Route::group(array('before' => 'authfront'), function() {

    Route::group(['middleware' => ['authfront']], function () {

        ######  this  is  use for  save  review  and  rating  ######
        Route::post('/restaurant/add-review/{restaurantid}', 'HomeController@saveReviews');
        Route::any('user-addresses', 'CartController@userAddresses');
        Route::any('checkout', 'CartController@checkout');
        Route::any('payment', 'CartController@payment');
        Route::any('add-address', 'CartController@addAddress');
        Route::any('deliver-detail', 'CartController@deliverDetail');
        ###### dashboard ######
        Route::any('edit-profile', array(
            'as' => 'edit-profile',
            'uses' => 'MyaccountController@editProfile'
        ));
    });

############################################### Front Routing end here ##################################################################


################################################################# Admin Routing start here###################################################


//Route::group(array('prefix' => 'admin'), function()
    Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function () {

        Route::group(array('before' => 'guestadmin'), function () {
            Route::get('', 'AdminLoginController@login');
            Route::any('/login', 'AdminLoginController@login');
            Route::get('/forget_password', 'AdminLoginController@forgetPassword');
            Route::get('/reset_password/{validstring}', 'AdminLoginController@resetPassword');
            Route::post('/send_password', 'AdminLoginController@sendPassword');
            Route::post('/save_password', 'AdminLoginController@resetPasswordSave');
        });

        Route::group(array('before' => 'authadmin'), function () {

            Route::any('download-sample-csv-file', array(
                'as' => 'download-sample-csv-file',
                'uses' => 'RestaurantController@downloadSampleCsv'
            ));
            #### for get states and cities route ####
            Route::any('ajaxdata/get_states', 'AdminAjaxdataController@getStates');
            Route::any('ajaxdata/get_cities', 'AdminAjaxdataController@getCities');

            Route::get('/logout', 'AdminLoginController@logout');
            Route::get('/dashboard', 'AdminDashBoardController@showdashboard');
            Route::get('/myaccount', 'AdminDashBoardController@myaccount');
            Route::post('/myaccount', 'AdminDashBoardController@myaccountUpdate');

            ### email manager  routing ###

            Route::get('/email-manager', 'EmailtemplateController@listTemplate');
            Route::get('/email-manager/add-template', 'EmailtemplateController@addTemplate');
            Route::post('/email-manager/add-template', 'EmailtemplateController@saveTemplate');
            Route::get('/email-manager/edit-template/{id}', 'EmailtemplateController@editTemplate');
            Route::post('/email-manager/edit-template/{id}', 'EmailtemplateController@updateTemplate');
            Route::post('/email-manager/get-constant', 'EmailtemplateController@getConstant');

            ### cms manager  routing ###
            Route::get('/cms-manager', 'CmspagesController@listCms');
            Route::post('/cms-manager', 'CmspagesController@listCms');
            Route::get('cms-manager/add-cms', 'CmspagesController@addCms');
            Route::post('cms-manager/add-cms', 'CmspagesController@saveCms');
            Route::get('cms-manager/edit-cms/{id}', 'CmspagesController@editCms');
            Route::post('cms-manager/edit-cms/{id}', 'CmspagesController@updateCms');
            Route::get('cms-manager/update-status/{id}/{status}', 'CmspagesController@updateCmsStatus');


            ### Email Logs Manager routing ###
            Route::get('/email-logs', 'EmailLogsController@listEmail');
            Route::any('/email-logs/email_details/{id}', 'EmailLogsController@EmailDetail');

            ### setting manager  routing ###
            Route::get('/settings', 'SettingsController@listSetting');
            Route::get('/settings/add-setting', 'SettingsController@addSetting');
            Route::post('/settings/add-setting', 'SettingsController@saveSetting');
            Route::get('/settings/edit-setting/{id}', 'SettingsController@editSetting');
            Route::post('/settings/edit-setting/{id}', 'SettingsController@updateSetting');
            Route::get('/settings/prefix/{slug}', 'SettingsController@prefix');
            Route::post('/settings/prefix/{slug}', 'SettingsController@updatePrefix');
            Route::delete('/settings/delete-setting/{id}', 'SettingsController@deleteSetting');
            Route::any('/settings/manage-videos', 'SettingsController@manageVideos');

            ### Dropdown manager  module  routing start here ###
            Route::get('/dropdown-manager/{type}', 'DropDownController@listDropDown');
            Route::post('/dropdown-manager/{type}', 'DropDownController@listDropDown');
            Route::get('dropdown-manager/add-dropdown/{type}', 'DropDownController@addDropDown');
            Route::post('dropdown-manager/add-dropdown/{type}', 'DropDownController@saveDropDown');
            Route::get('dropdown-manager/edit-dropdown/{id}/{type}', 'DropDownController@editDropDown');
            Route::post('dropdown-manager/edit-dropdown/{id}/{type}', 'DropDownController@updateDropDown');
            Route::get('dropdown-manager/delete-dropdown/{id}/{type}', 'DropDownController@deleteDropDown');
            Route::delete('dropdown-manager/delete-dropdown/{id}/{type}', 'DropDownController@deleteDropDown');
            Route::get('/dropdown-manager/update-cusine-status/{cusineid}/{status}', 'DropDownController@cusineDislayStatus');

            ### faq  module  routing ###
            Route::get('/faqs-manager', 'FaqsController@listFaq');
            Route::post('/faqs-manager', 'FaqsController@listFaq');
            Route::get('faqs-manager/add-faqs', 'FaqsController@addFaq');
            Route::post('faqs-manager/add-faqs', 'FaqsController@saveFaq');
            Route::get('faqs-manager/edit-faqs/{id}', 'FaqsController@editFaq');
            Route::post('faqs-manager/edit-faqs/{id}', 'FaqsController@updateFaq');
            Route::get('faqs-manager/update-status/{id}/{status}', 'FaqsController@updateFaqStatus');
            Route::delete('faqs-manager/delete-faqs/{id}', 'FaqsController@deleteFaq');

            ## users routing start here ###
            Route::get('users', 'UsersController@listUsers');
            Route::post('users', 'UsersController@listUsers');
            Route::get('users/view-user/{id}', 'UsersController@viewUser');
            Route::get('users/update-status/{id}/{status}', 'UsersController@updateUserStatus');
            Route::delete('users/delete-user/{id}', 'UsersController@deleteUser');
            Route::get('users/verify-user/{id}', 'UsersController@verifiedUser');
            Route::get('users/add-user', 'UsersController@addUser');
            Route::post('users/add-user', 'UsersController@saveUser');
            Route::get('users/edit-user/{id}', 'UsersController@editUser');
            Route::post('users/edit-user/{id}', 'UsersController@updateUser');

            ### Restaurant  module  routing	###

            Route::get('/restaurant-manager', 'RestaurantController@listRestaurant');
            Route::post('/restaurant-manager', 'RestaurantController@listRestaurant');
            Route::get('restaurant-manager/add-restaurant', 'RestaurantController@addRestaurant');
            Route::post('restaurant-manager/save-restaurant', 'RestaurantController@saveRestaurant');
            Route::get('restaurant-manager/edit-restaurant/{id}', 'RestaurantController@editRestaurant');
            Route::post('restaurant-manager/update-restaurant/{id}', 'RestaurantController@updateRestaurant');
            Route::get('restaurant-manager/update-restaurant-status/{id}/{status}', 'RestaurantController@updateRestaurantStatus');
            Route::get('restaurant-manager/view-restaurant/{id}', 'RestaurantController@viewRestaurant');
            Route::delete('restaurant-manager/delete-restaurant/{id}', 'RestaurantController@deleteRestaurant');
            Route::any('restaurant-manager/recommended-status/{id}/{status}', 'RestaurantController@recommendedStatus');

            Route::any('restaurant-manager/add-meal/{restroId}', 'RestaurantController@addMeal');
            Route::any('restaurant-manager/edit-meal/{restroId}/{mealId}', 'RestaurantController@editMeal');
            Route::any('restaurant-manager/delete-meal/{mealId}', 'RestaurantController@deleteMeal');
            Route::any('restaurant-manager/update-meal-status/{mealId}/{status}', 'RestaurantController@updateMealStatus');

            Route::any('restaurant-manager/import-meal/{restroId}', 'RestaurantController@importMeal');

            ### order management  module  routing	###

            Route::get('/order-manager', 'OrderController@listOrder');
            Route::post('/order-manager', 'OrderController@listOrder');
            Route::get('order-manager/view-order/{id}', 'OrderController@viewOrderDetail');
            Route::any('order-manager/update-order-status/{id}/{status}', 'OrderController@updateOrderStatus');
            Route::any('order-manager/paid-status/{id}', 'OrderController@markAsPaid');

            ### Language setting start ###
            Route::get('/language-settings', 'LanguageSettingsController@listLanguageSetting');
            Route::get('/language-settings/add-setting', 'LanguageSettingsController@addLanguageSetting');
            Route::post('/language-settings/add-setting', 'LanguageSettingsController@saveLanguageSetting');
            Route::get('/language-settings/edit-setting/{id}', 'LanguageSettingsController@editLanguageSetting');
            Route::post('/language-settings/edit-setting/{id}', 'LanguageSettingsController@updateLanguageSetting');

            ### language routing ###
            Route::get('language', 'LanguageController@listLanguage');
            Route::get('language/add-language', 'LanguageController@addLanguage');
            Route::post('language/save-language', 'LanguageController@saveLanguage');
            Route::any('language/delete-language/{id}', 'LanguageController@deleteLanguage');
            Route::get('language/update-status/{id}/{status}', 'LanguageController@updateLanguageStatus');
            Route::any('language/default/{id}/{langCode}/{folderCode}', 'LanguageController@updateDefaultLanguage');

            ### text setting ###
            Route::get('text-setting', 'TextSettingController@textList');
            Route::get('text-setting/add-new-text', 'TextSettingController@addText');
            Route::any('text-setting/save-new-text', 'TextSettingController@saveText');
            Route::any('text-setting/edit-new-text/{id}', 'TextSettingController@editText');
            Route::any('text-setting/update-new-text/{id}', 'TextSettingController@updateText');
            Route::any('text-setting/delete-text/{id}', 'TextSettingController@deleteText');

            ###  restaurant   review  list ###
            Route::get('/restaurant-manager/reviews/{restaurantid}', 'RestaurantController@listReview');
            Route::post('/restaurant-manager/reviews/{restaurantid}', 'RestaurantController@listReview');
            ### update restaurant review status ###
            Route::get('/restaurant-manager/update-review-status/{reviewid}/{status}', 'RestaurantController@reviewDislayStatus');
            ###  delete  particular review ###
            Route::any('/restaurant-manager/delete-review/{reviewid}', 'RestaurantController@deleteReview');
            Route::any('/restaurant-manager/manage-time/{restaurantid}', 'RestaurantController@manageTime');


            ##Block manager  module  routing start here
            Route::get('/block-manager', 'BlockController@listBlock');
            Route::post('/block-manager', 'BlockController@listBlock');
            Route::get('block-manager/add-block', 'BlockController@addBlock');
            Route::post('block-manager/add-block', 'BlockController@saveBlock');
            Route::get('block-manager/edit-block/{id}', 'BlockController@editBlock');
            Route::post('block-manager/edit-block/{id}', 'BlockController@updateBlock');
            Route::get('block-manager/update-status/{id}/{status}', 'BlockController@updateBlockStatus');
            Route::delete('block-manager/delete-block/{id}', 'BlockController@deleteBlock');
        });
    });
### error page routing ###
    Route::any('{slug}', function ($slug) {
        if (Request::segment(1) == 'admin') {
            return View::make('admin.layouts.404');
        } else {
            return View::make('errors.404');
        }

    });

//App::missing(function($exception)
//{
//    if(Request::segment(1)=='admin'){
//        if(Auth::check()){
//            return View::make('admin.layouts.404');
//        }else{
//            return Redirect::to('admin/login');
//        }
//    }else{
//        return View::make('errors.404');
//    }
//});


    Route::any('{catchall}', function () {
        if (Request::segment(1) == 'admin') {
            if (Auth::check()) {
                return View::make('admin.layouts.404');
            } else {
                return Redirect::to('admin/login');
            }
        } else {
            return View::make('errors.404');
        }
    })->where('catchall', '.*');

});
