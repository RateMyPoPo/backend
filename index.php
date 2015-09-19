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

// Retrieves user by id
$app->get('/user/{id}', function ($id) use ($di) {
//    $result = $di['db']->query("SELECT * FROM user where id = ".$id);
//    $result->setFetchMode(Phalcon\Db::FETCH_ASSOC);
    $user = User::findFirst($id);
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
    $response->setHeader("Access-Control-Allow-Origin", "*");
    return $response;
});


$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo 'This is crazy, but this page was not found!';
});

$app->handle();