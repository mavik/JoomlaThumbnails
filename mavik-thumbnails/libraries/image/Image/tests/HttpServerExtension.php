<?php
namespace Mavik\Image\Tests;

use PHPUnit\Runner\AfterLastTestHook;
use PHPUnit\Runner\BeforeFirstTestHook;

class HttpServerExtension implements BeforeFirstTestHook, AfterLastTestHook
{
    public function executeBeforeFirstTest(): void
    {
        HttpServer::start();
    }

    public function executeAfterLastTest(): void
    {
        HttpServer::stop();
    }
}
