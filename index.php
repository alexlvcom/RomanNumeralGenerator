<?php

use RomanNumerals\Container;
use RomanNumerals\Controllers\Controller;
use RomanNumerals\Controllers\ConvertController;
use RomanNumerals\Libs\RomanNumeralGenerator;
use RomanNumerals\Router;
use RomanNumerals\Application;

error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__.'/vendor/autoload.php';

$routes = [
    [
        'GET',
        '/api/generate/{arabicNumber:\w+}',
        ConvertController::class.'@generate'
    ],
    [
        'GET',
        '/api/parse/{romanNumber:\w+}',
        ConvertController::class.'@parse'
    ],
];

$container = new Container();

$container->bind(ConvertController::class, function () {
    $object = new ConvertController();
    $object->setRomanNumeralGenerator(new RomanNumeralGenerator());
    return $object;
});

$container->bind(Controller::class, function () {
    return new ConvertController();
});

$app = new Application($container, new Router($routes));
$app->start();
