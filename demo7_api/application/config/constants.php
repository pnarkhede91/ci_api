<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');
define("FIREBASE_API_KEY", "AAAApDp5HAI:APA91bEFoBmqIyjqd4kFPr9F8fN67KJ6vvlv2-Ubxu6FOgl1apK7D_TkPHq-wQjhIlDoUfxYiR6yNp9glGEQw-YH5Uag3tLxxUZvmClxeBOk9-YD23Dwpcjx8ztDLKM2V5Ouy5eC8B_P");
/* Common Constant */
define('SITE_NAME','Nilesh');
define('ADMIN_END','admin');

/*Currency Symbol*/
define('CURRENCY','₹');

/* SEPERATER */
define('DS','/');

/* folder name */
define('UPLOADS','uploads');
define('PRODUCT','product');
define('PRODUCT_IMAGE',PRODUCT.DS.'image');
define('PRODUCT_VIDEO',PRODUCT.DS.'video');
define('PRODUCT_PRIVACY_POLICY',PRODUCT.DS.'privacy_policy');

/* Database Tabel Name Constant */
define('TBL_APP_USERS','tbl_app_users');
define('TBL_USERS','tbl_users');
define('TBL_COUNTRY','tbl_country');
define('TBL_STATE','tbl_state');
define('TBL_CITY','tbl_city');
define('TBL_BRANDS','tbl_brands');
define('TBL_CATEGORY','tbl_category');
define('TBL_SUB_CATEGORY','tbl_sub_category');
define('TBL_PRODUCT','tbl_product');
define('TBL_PRODUCT_IMG_VIDEO','tbl_product_img_video');
define('TBL_SUBSCRIBER','tbl_subscriber');
define('TBL_CART','tbl_cart');
define('TBL_WISHLIST','tbl_wishlist');
define('TBL_PRODUCT_REVIEW','tbl_product_review');
define('TBL_ORDERS','tbl_orders');
define('TBL_ORDER_DETAILS','tbl_order_details');
define('TBL_ORDER_PAYMENT_DETAILS','tbl_order_payment_details');
define('TBL_ORDER_REFERENCE','tbl_order_reference');
define('TBL_BILLING_ADDRESS','tbl_billing_address');
define('TBL_PRODUCT_GROUP','tbl_product_group');
define('TBL_PRODUCT_GROUP_CHAT','tbl_product_group_chat');

/* Date Constant*/
define('DB_CURRENT_DATE',date('Y-m-d H:i:s'));
define('DB_CURRENT_DATE_FORMAT','Y-m-d');
define('VIEW_CURRENT_DATE_FORMAT','d-m-Y');
define('VIEW_CURRENT_DATE_TIME_FORMAT','d-m-Y H:i:s');

/* status constant*/
define('IS_DELETED_YES',1); // record delted
define('IS_DELETED_NO',0); // record not deleted
define('IS_ACTIVE_YES',1); // active
define('IS_ACTIVE_NO',0); // inactive
define('IS_ADMIN_APPROVE_YES',1); // approve by admin
define('IS_ADMIN_APPROVE_NO',0); // not approve by admin

/*paths*/
define('BRAND_IMAGES',UPLOADS.DS.'brands');

/*product type image or video*/
define('PRODUCT_TYPE_IMG',0);
define('PRODUCT_TYPE_VIDEO',1);

/*GST Tax amount*/
define('TAX_PERCENTAGE',5);

/*email settings*/
define('FROM_EMAIL','brand@test.com');
define('FROM_NAME','Brand');
define('REGARDS','Team Brand');
define('PROTOCOL','smtp');
define('SMTP_HOST','ssl://smtp.googlemail.com');
define('SMTP_PORT',465);
define('SMTP_USER','kbsofttech10@gmail.com');
define('SMTP_PASS','kailas@2201');
define('MAILTYPE','html');
define('CHARSET','utf-8');
/* End of file constants.php */
/* Location: ./application/config/constants.php */