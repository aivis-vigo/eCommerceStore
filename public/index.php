<?php declare(strict_types=1);

require_once "../vendor/autoload.php";

use App\Database\Seeds\DatabaseSeeder;

$seeder = new DatabaseSeeder();
$seeder->run();

$client = new GuzzleHttp\Client();
