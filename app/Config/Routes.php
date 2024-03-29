<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->get('/hello', 'Hello::index');
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

$routes->get('/', 'Home::index');
$routes->get('/update_profile', 'Home::updateProfile');
$routes->get('/verify_email', 'Home::verifyEmail');
$routes->get('/verify/(:any)', 'Home::checkEmail/$1');
$routes->post('/update_username', 'Home::updateUsername');
$routes->post('/update_password', 'Home::updatePassword');
$routes->post('/update_email', 'Home::updateEmail');
$routes->get('/stats', 'Main::get_stats');
$routes->get('/download', 'Main::download');
$routes->post('/main/bookmark', 'Main::bookmark');

$routes->get('/unbookmark/(:num)', 'Home::unbookmark/$1');


$routes->post('/main/endorse', 'Main::endorse');
$routes->get('/main/answer_question/ajax', 'Main::answer_question');
$routes->post('/main/boost/ajax', 'Main::boost');
$routes->post('/new_subject', 'Home::newSubject');
$routes->get('/forgot', 'Login::forgot_password');
$routes->post('login/change_pass', 'Login::change_password');
$routes->post('login/confirm_pass', 'Login::confirm_pass');
$routes->match(['get','post'],'/main/(answer_question/ajax)', 'Main::answer_question');
$routes->get('/(.*)/ajax', 'Main::submit_form');
$routes->match(['get','post'],'/(.*/ajax)', 'Main::submit_form');
$routes->post('/main/search', 'Main::search');
$routes->get('/main/(:num)', 'Main::index/$1');
$routes->get('/main', 'Main::index');
$routes->get('/login/logout','Login::logout');
$routes->get('/login', 'Login::index');
$routes->post('/donate/check_donation', 'Donate::check_donation');
$routes->get('/donate', 'Donate::index');
$routes->get('/signup', 'Signup::index');
$routes->post('/login/check_login', 'Login::check_login');
$routes->post('/signup/check_signUp', 'Signup::check_signUp');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
