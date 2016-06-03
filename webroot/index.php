<?php 
/**
 * This is a Anax pagecontroller.
 *
 */

// Get environment & autoloader and the $app-object.
require __DIR__.'/config_with_app.php'; 

$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);

// Databas
$di->setShared('db', function() {
    $db = new \Anax\Database\CDatabaseBasic();
    $db->setOptions(require ANAX_APP_PATH . 'config/database_sqlite.php');
    $db->connect();
    return $db;
});

// Användare
$di->set('UserController', function() use ($di) {
    $controller = new \Anax\User\UserController();
    $controller->setDI($di);
    return $controller;
});

// Frågor
$di->set('QuestionController', function() use ($di) {
    $controller = new \Anax\Question\QuestionController();
    $controller->setDI($di);
    return $controller;
});

// Taggar
$di->set('TagController', function() use ($di) {
    $controller = new \Anax\Tag\TagController();
    $controller->setDI($di);
    return $controller;
});

// Kommentarer
$di->set('CommentController', function() use ($di) {
    $controller = new \Anax\Comment\CommentController();
    $controller->setDI($di);
    return $controller;
});

// Form
$di->set('form', '\Anax\HTMLForm\CForm'); 

$app->theme->configure(ANAX_APP_PATH . 'config/theme_geogebra.php');
$app->navbar->configure(ANAX_APP_PATH . 'config/navbar_geogebra.php');

$app->router->add('', function() use ($app) {
	$app->theme->setTitle("Startsida");	
	$app->views->add('geogebra/startsida', [
		'fragor' 		=> $app->QuestionController->getLastQuestions(3),
		'anvandare' => $app->UserController->getMostActive(3),
		'tags'			=> $app->TagController->getPopTags(5),
	]);
});
 
$app->router->add('fragor', function() use ($app) {
	$app->theme->setTitle("Frågor");
	$app->dispatcher->forward([
        'controller' => 'question',
        'action'     => 'view',				
  ]);
});

$app->router->add('taggar', function() use ($app) {
	$app->theme->setTitle("Taggar");
	$app->dispatcher->forward([
        'controller' => 'tag',
        'action'     => 'view',				
  ]);
});

$app->router->add('anvandare', function() use ($app) {
	$app->theme->setTitle("Användare");
	$app->dispatcher->forward([
        'controller' => 'user',
        'action'     => 'view',				
  ]);
});
 
$app->router->add('stall_fraga', function() use ($app) {
	$app->dispatcher->forward([
        'controller' => 'question',
        'action'     => 'add',				
  ]);
});

$app->router->add('om', function() use ($app) {
	$app->theme->setTitle("Om");
	$app->views->add('geogebra/om');
});

$app->router->handle();
$app->theme->render();
