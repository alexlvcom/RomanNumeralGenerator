<?php

namespace RomanNumerals;

error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__.'/vendor/autoload.php';

$routes = [
    [
        'GET',
        '/api/generate/{arabicNumber:\d+}',
        'RomanNumerals\ConvertController@generate'
    ],
    [
        'GET',
        '/api/parse/{romanNumber:\w+}',
        'RomanNumerals\ConvertController@parse'
    ],
];

$container = new Container();
$container->bind('RomanNumerals\ConvertController', function () {
    $object = new ConvertController();
    $object->setRomanNumeralGenerator(new RomanNumeralGenerator());
    return $object;
});
$container->bind('RomanNumerals\Controller', function () {
    return new ConvertController();
});

$app = new Application($container, new Router($routes));
$app->start();
