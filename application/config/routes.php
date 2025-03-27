<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
//untuk backend
$route['default_controller'] 		= 'landing';
$route[CI_LOGIN_PATH]				= 'logintoadmin';
$route[CI_OAUTH_GOOGLE]				= 'logintoadmin/google';
$route[CI_OAUTH_FACEBOOK]			= 'logintoadmin/facebook';
$route[CI_ADMIN_PATH.'/(:any)'] 	= 'adminapps/$1';
$route[CI_ADMIN_PATH]				= 'adminapps';
$route[CI_RECOVERY_PATH]			= 'recoverypwd';
$route[CI_RECOVERY_PATH.'/(:any)']	= 'recoverypwd/$1';
$route['adminapps']					= 'show_404';
$route['recoverypwd']				= 'show_404';
$route['logintoadmin']				= 'show_404';
$route['logintoadmin/google']		= 'show_404';
$route['logintoadmin/facebook']		= 'show_404';
$route['404_override'] 				= '';
$route['translate_uri_dashes'] 		= FALSE;
//untuk frontend
$route["ebook"] 					= "ebook/daftar/";
$route["ebook".'/:any' ]  			= "ebook/daftar/";
$route['post/(:any)'] 				= 'posting/post/$1';
$route['post/(:any)/(:any)'] 		= 'posting/post/$1/$2';
$route['post'] 						= 'posting/post';
$route[CI_POST_PATH.'(:any)'] 		= 'posting/baca/$1';
$route[CI_POST_PATH] 				= 'posting/baca';

