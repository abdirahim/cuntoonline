<?php
$host = null;
if (!empty($_SERVER['HTTP_HOST'])) {
    $host = $_SERVER['HTTP_HOST'];
}
return [

    /*
    |--------------------------------------------------------------------------
    | User Defined Variables
    |--------------------------------------------------------------------------
    |
    | This is a set of variables that are made specific to this application
    | that are better placed here rather than in .env-old file.
    | Use config('your_key') to get the values.
    |
    */
    'SUBDIR' => '',
    'FFMPEG_CONVERT_COMMAND' => '',


    'ADMIN_FOLDER' => 'admin/',
    'DS' => DIRECTORY_SEPARATOR,
    'ROOT' => base_path(),
    'APP_PATH' => app_path(),

   'WEBSITE_URL' => $host. '/',
  // 'WEBSITE_URL' => $host . '/' . '',
   //'WEBSITE_URL' => 'http://cuntoonline.develop/' . '',
   'WEBSITE_JS_URL' => $host . '/js/',
   'WEBSITE_CSS_URL' => $host . '/css/',
   'WEBSITE_IMG_URL' => $host . '/img/',
   'WEBSITE_UPLOADS_ROOT_PATH' => base_path() . DIRECTORY_SEPARATOR . 'uploads' .DIRECTORY_SEPARATOR,
   'WEBSITE_UPLOADS_URL' => 'http://' . $host . 'uploads/',
   'WEBSITE_PUBLIC_UPLOADS_URL' => 'http://' . $host . 'uploads/',

   'WEBSITE_ADMIN_URL' => $host.'admin/',
   'WEBSITE_ADMIN_IMG_URL' => $host.'admin/img/',
   'WEBSITE_ADMIN_JS_URL' => $host.'admin/js/',
   'WEBSITE_ADMIN_FONT_URL' => $host.'admin/fonts/',
   'WEBSITE_ADMIN_CSS_URL', $host.'admin/css/',

   'SETTING_FILE_PATH' => app_path() . DIRECTORY_SEPARATOR . 'settings.php',
   'MENU_FILE_PATH' => app_path() . DIRECTORY_SEPARATOR . 'menus.php',

   'CK_EDITOR_URL' => $host . 'uploads/ckeditor_pic/',
   'CK_EDITOR_ROOT_PATH', base_path() . DIRECTORY_SEPARATOR . 'uploads' .DIRECTORY_SEPARATOR . 'ckeditor_pic' . DIRECTORY_SEPARATOR,

   'RESTAURANT_IMAGE_URL' => $host . 'uploads/restaurant_img/',
   'RESTAURANT_IMAGE_ROOT_PATH' => base_path() . DIRECTORY_SEPARATOR . 'uploads' .DIRECTORY_SEPARATOR .  'restaurant_img' . DIRECTORY_SEPARATOR,

    'MASTERS_IMAGE_URL'       => $host . 'uploads/masters/',
    'MASTERS_IMAGE_ROOT_PATH' => base_path() . DIRECTORY_SEPARATOR . 'uploads' .DIRECTORY_SEPARATOR .  'masters' . DIRECTORY_SEPARATOR,

    'Delivery_charge'                   => '3',
    'Contact_phone'                     => '063 388 028',
    'Contact_email'                     => 'info@cuntoonline.com',
    'ZAAD_PAYMENT'                      => '1',
    'EDAHAB_PAYMENT'                    => '2',
    'Site_title'                        =>  'Cunto Online',
    'Site_order_email_address'          =>'abdi_rahim1@yahoo.co.uk',
    'Site_email'                        =>  'info@cuntoonline.com',
    'default_language_language_code'    => '1',
    'Site_meta_description'                => 'Cunto Online',
    'Site_meta_keywords'                => 'Cunto Online',
    'Reading_records_per_page'          => '20',

    'ADMIN_ID'   => '1',
    'FRONT_USER' => '2',

    'DELIVERY'   => '1',
    'COLLECTION' => '0',

    'Monday'    => '1',
    'Tuesday'   => '2',
    'Wednesday' => '3',
    'Thursday'  => '4',
    'Friday'    => '5',
    'Saturday'  => '6',
    'Sunday'    => '7',

//define('PCOLLECTION',2);
//
//define('PROCESSIONG',0);
//define('ACCEPTED',1);
//define('REJECTED',2);
//define('COMPLETED',3);
//
//define('STARTERS',1);
//define('MAINS',2);
//define('SIDES',3);
//define('DESSERTS',4);
//define('BEVERAGES',5);

//// status
  'ACTIVE' => '1',
  'INACTIVE' => '0',

    //// account verified or not verified
  'VERIFIED'   => '1',
  'UNVERIFIED' => '0',

  'IMAGE_EXTENSION' => 'jpeg,jpg,png,gif,bmp',
  'PDF_EXTENSION' => 'pdf',
  'DOC_EXTENSION' => 'doc,xls',
  'VIDEO_EXTENSION' => 'mpeg,avi,mp4,webm,flv,3gp,m4v,mkv,mov,moov',

  'default_language.language_code' => '1',

];



//Config::set("Contact.email", "info@cuntoonline.com");
//Config::set("Contact.map", "https://maps.google.com/maps?ll=9.5489,44.064354&z=12&t=m&hl=en-GB&gl=US&mapclient=embed&q=Hargeisa%20Somalia");
//Config::set("Contact.phone", "063 388 0284");
//Config::set("default_language.folder_code", "en");
//Config::set("default_language.language_code", "1");
//Config::set("default_language.name", "English");
//Config::set("Delivery.charge", "3");
//Config::set("Email.client", "gmail.com");
//Config::set("Email.host", "smtp.gmail.com");
//Config::set("Email.password", "cunto_online");
//Config::set("Email.port", "25");
//Config::set("Email.timeout", "30");
//Config::set("Email.username", "onlinerestaurant2016@gmail.com");
//Config::set("Reading.date_format", "d-M-Y h:ia");
//Config::set("Reading.records_per_page", "20");
//Config::set("setting_social.fb_app_id", "1549862465333504");
//Config::set("setting_social.fb_secret_id", "d5c1edb0ea7da5bddeb74df0af0c0626");
//Config::set("setting_social.google_app_id", "650456520228-t3it61lg3fmosqsl2k1prmg3burmoa0k.apps.googleusercontent.com");
//Config::set("setting_social.google_redirect_url", "http://cuntoonline.com/login-google");
//Config::set("setting_social.google_secret_id", "vDBTaxat_-qhA1fFEuqvydh6");
//Config::set("setting_social.twitter_app_id", "xf1mHb0Z5DykGDqjZZULNbyoo");
//Config::set("setting_social.twitter_secret_id", "1J7hjWHurB8kIsdj8o1v8yh4nL123bFkxfrQomn1bFZUbvo91K");
//Config::set("Site.copyright_text", "Copyright © 2015 Cunto Online food home. All rights reserved.");
//Config::set("Site.email", "info@cuntoonline.com");
//Config::set("Site.meta_description", "Cunto Online");
//Config::set("Site.meta_keywords", "Cunto Online");
//Config::set("Site.order_email_address", "abdi@responsivetech.co.uk");
//Config::set("Site.title", "Cunto Online");
//Config::set("Social.facebook", "https://www.facebook.com/hargeisaonlinefood/?ref=aymt_homepage_panel");
//Config::set("Social.gplus", "https://plus.google.com");
//Config::set("Social.instagram", "http://instagram.com");
//Config::set("Social.pinterest", "http://pinterest.com");
//Config::set("Social.twitter", "http://twitter.com");
//Config::set("Social.youtube", "http://youtube.com");




//
//$config	=	array();
//
define('ALLOWED_TAGS_XSS', '<a><strong><b><p><br><i><font><img><h1><h2><h3><h4><h5><h6><span></div><em><table><ul><li><section><thead><tbody><tr><td><iframe><div></span>');
//
//// Constant for user role id
//

//
//Config::set("Site.currency", "$");
//Config::set("Site.currencyCode", "USD");
//
//Config::set('defaultLanguage', 'English');
//Config::set('defaultLanguageCode', 'en');
//
//Config::set('default_language.language_code', '1');
//
//Config::set('default_language.message', 'All the fields in English language are mandatory.');
//
//Config::set('newsletter_template_constant',array('USER_NAME'=>'USER_NAME','TO_EMAIL'=>'TO_EMAIL','WEBSITE_URL'=>'WEBSITE_URL','UNSUBSCRIBE_LINK'=>'UNSUBSCRIBE_LINK'));
//

//
//Config::set('food_categories',array(
//    STARTERS			=> 'Starters',
//    MAINS				=> 'Mains',
//    SIDES				=> 'Sides',
//    DESSERTS			=> 'Desserts',
//    BEVERAGES 			=> 'Beverages',
//));
//
// order status

//
//Config::set('order_status',array(
//    PROCESSIONG			=> 'Processing',
//    ACCEPTED			=> 'Accepted',
//    REJECTED			=> 'Rejected',
//    COMPLETED			=> 'Completed',
//
//));
//
//
//Config::set('text_search',array(
//    'dashboard.'=> 'Dashboard',
//    'user_managmt.'=> 'User Management',
//    'management.'=> 'Management',
//    'restaurant.'=> ' Restaurant Management',
//    'settings.'=> 'Settings',
//));
//

//
//Config::set('days',array(
//    Monday		=>'Monday',
//    Tuesday		=>'Tuesday',
//    Wednesday	=>'Wednesday',
//    Thursday	=>'Thursday',
//    Friday		=>'Friday',
//    Saturday	=>'Saturday',
//    Sunday		=>'Sunday'
//));
//



