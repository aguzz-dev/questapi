<?php
require 'vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

define('APP_NAME', $_ENV['APP_NAME']);
define('DB_HOST', $_ENV['DB_HOST']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASS', $_ENV['DB_PASS']);
define('DB_NAME', $_ENV['DB_NAME']);
define('TIME_ZONE', date_default_timezone_set($_ENV['TIME_ZONE']));
define('SECRET_KEY', $_ENV['SECRET_KEY']);
