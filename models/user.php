<?php
namespace Fedup\Models;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Message;

class User extends Model
{
    public $id;
    public $first_name;
    public $last_name;

}