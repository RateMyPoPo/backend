<?php
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Message;

class Interaction extends Model
{
    public $id;
    public $username;
    public $file_name;
    public $longitude;
    public $latitude;
    public $timestamp;

}