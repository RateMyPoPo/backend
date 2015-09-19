<?php

use Phalcon\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\DI\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;

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
            "username" => "user",
            "password" => "",
            "dbname"   => "fedup"
        )
    );
});

// Create and bind the DI to the application
$app = new Micro($di);

// Retrieves all users
$app->get('/api/users', function () use ($app) {
    $phql = "SELECT * FROM user ORDER BY id";
    $robots = $app->modelsManager->executeQuery($phql);

    $data = array();
    foreach ($robots as $robot) {
        $data[] = array(
            'id'   => $robot->id
        );
    }

    echo json_encode($data);
});


$app->handle();