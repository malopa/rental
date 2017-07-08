<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__.'/../vendor/autoload.php';

$app = new \Slim\App;


require_once('../app/api/agent.php');
require_once('../app/api/customer.php');
require_once('../app/api/house.php');
require_once('../app/api/map.php');
require_once('../app/api/order.php');
require_once('../app/api/search.php');

$app->run();
