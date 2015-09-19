<?php

use Phalcon\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\DI\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;
use Phalcon\Http\Response;

// Use Loader() to autoload our model
$loader = new Loader();

$loader->registerDirs(
    array(
        __DIR__ . '/models/'
    )
)->register();

$di = new FactoryDefault();

// Set up the database service
$di->set('db', function () {
    return new PdoMysql(
        array(
            "host"     => "localhost",
            "username" => "root",
            "password" => "",
            "dbname"   => "fedup"
        )
    );
});

// Create and bind the DI to the application
$app = new Micro($di);

$app->get('/', function () {
    echo "<h1>Welcome!</h1>";
});

// Retrieves all users
$app->get('/user', function () use ($di) {
    $result = $di['db']->query("SELECT * FROM user");
    $result->setFetchMode(Phalcon\Db::FETCH_ASSOC);
    $response = new Response();
    $response->setContent(json_encode($result->fetch()));
    return $response;
});

$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo 'This is crazy, but this page was not found!';
});

echo json_encode($_GET);

$app->handle();