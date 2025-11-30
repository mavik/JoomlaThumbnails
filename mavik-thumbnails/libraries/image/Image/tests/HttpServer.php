<?php
namespace Mavik\Image\Tests;

class HttpServer
{
    static $isStarted = false;

    public static function start(): void
    {
        if (self::$isStarted) {
            return;
        } 
        
        $webRoot = __DIR__ . '/resources/images';
        shell_exec("php -S localhost:8888 -t {$webRoot} > /dev/null 2>&1 &");
        $count = 0;
        do {
            usleep(10000);
            $content = @file_get_contents('http://localhost:8888');           
        } while (empty($content) && $count++ < 50);
        if (empty($content)) {
            throw new \Exception('HTTP server cannot be started');
        }
        
        self::$isStarted = true;
    }    
}