<?php require('vendor/autoload.php');

date_default_timezone_set("Europe/Ljubljana");

$dot_env = Dotenv\Dotenv::create(__DIR__);
$dot_env->load();

// Our test Bank class object
$bank = new \IPROMTestApp\Bank();
$bank::randomize();
$bank::balance();
$bank::transactions();
$bank::dailyTransactions(4);
$bank::negativeBalance();