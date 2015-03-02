<?php
require 'vendor/autoload.php';
require 'models/Model.php';


$aBitOfInfo = function (\Slim\Route $route) {
    echo "Current route is: " . $route->getName();
};

$app = new \Slim\Slim(array(
    'mode' => 'development',
    'debug' => true
));


$app->get('/', function () use ($app) {
    $app->render('home.php');
});

$app->get('/color/:rgb_color', function ($rgb_color) use ($app) {
    $app->render('color.php', array('rgb_color' => $rgb_color));
});

/*harvest colors*/

/*$app->get('/get', function () use ($app) {
    $app->render('get.php');
});

$app->post('/get', function () use ($app) {

    echo Model::saveColors(json_decode($app->request->getBody()));

});*/



$app->run();