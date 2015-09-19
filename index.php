<?php

use Phalcon\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\DI\FactoryDefault;

// Use Loader() to autoload our model
$loader = new Loader();

$loader->registerDirs(
    array(
        __DIR__ . '/models/'
    )
)->register();

//Make a connection
$connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'dbname' => 'fedup',
));

//Reconnect
$connection->connect();

$statement = $db->prepare('SELECT * FROM fedup.user');
$result = $connection->executePrepared($statement);

echo json_encode($result);

//// Create and bind the DI to the application
//$app = new Micro($di);
//
//$app->get('/say/welcome/{name}', function ($name) {
//    echo "<h1>Welcome $name!</h1>";
//});
//
//// Retrieves all users
//$app->get('/api/users', function () use ($app) {
//    $phql = "SELECT * FROM user ORDER BY id";
//    $robots = $app->modelsManager->executeQuery($phql);
//
//    $data = array();
//    foreach ($robots as $robot) {
//        $data[] = array(
//            'id'   => $robot->id
//        );
//    }
//
//    echo json_encode($data);
//});
//
//
//$app->handle();