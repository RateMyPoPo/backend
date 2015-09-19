<?php

use Phalcon\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\DI\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;
use Phalcon\Http\Response;
use Phalcon\Http\Request;

require 'models/user.php'; //User

$di = new FactoryDefault();

// Set up the database service
$di->set('db', function () {
    return new PdoMysql(
        array(
            "host"     => "localhost",
            "username" => "root",
            "password" => "root",
            "dbname"   => "fedup"
        )
    );
});

// Create and bind the DI to the application
$app = new Micro($di);

$app->get('/', function () {
    echo "<h1>Welcome!</h1>";
});

// Retrieves user by id
$app->get('/user/{id}', function ($id) use ($di) {
//    $result = $di['db']->query("SELECT * FROM user where id = ".$id);
//    $result->setFetchMode(Phalcon\Db::FETCH_ASSOC);
    $user = User::findFirst(1);
    $response = new Response();
    $response->setContent(json_encode($user));
    return $response;
});

// Write a user
$app->post('/user', function () use ($di) {
    $request = new Request();
    $data = json_decode($request->getRawBody());
    $user = new User();
    $user->first_name = $data->first_name;
    $user->last_name = $data->last_name;
    
    if ($user->save() == false) {
        foreach ($user->getMessages() as $message) {
            error_log($message, "\n");
        }
    }

    $response = new Response();
    $response->setContent($user->id);
    return $response;
});

// Updates a user
$app->post('/user/{id}', function ($id) use ($di) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $result = $di['db']->query("UPDATE user SET first_name=$first_name, last_name=$last_name WHERE id=$id");
});

// Retrieves interaction by id
$app->get('/interaction/{id}', function ($id) use ($di) {
    $result = $di['db']->query("SELECT * FROM interaction where id = ".$id);
    $result->setFetchMode(Phalcon\Db::FETCH_ASSOC);
    $response = new Response();
    $response->setContent(json_encode($result->fetch()));
    return $response;
});


$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo 'This is crazy, but this page was not found!';
});

$app->handle();