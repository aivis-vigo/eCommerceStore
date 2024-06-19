<?php declare(strict_types=1);

namespace App\Controllers;

// todo: provide arguments and types

abstract class BaseController
{
    abstract public function index();

    abstract public function show();

    abstract public function create();

    abstract public function edit();

    abstract public function update();

    abstract public function destroy();
}