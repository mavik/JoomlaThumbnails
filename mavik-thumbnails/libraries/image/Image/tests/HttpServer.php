<?php
namespace Mavik\Image\Tests;

class HttpServer
{
    static $pid;

    public static function start(): void
    {
        if (isset(self::$pid)) {
            return;
        }
        $content = @file_get_contents('http://localhost:8888');
        if (!empty($content)) {
            return;
        }

        $webDir = __DIR__ . '/resources/images';
        $router = "{$webDir}/route.php";
        self::$pid = shell_exec("php -S localhost:8888 -t {$webDir} {$router} > /dev/null 2>&1 & echo $!");

        $count = 0;
        do {
            usleep(10000);
            $content = @file_get_contents('http://localhost:8888');
        } while (empty($content) && $count++ < 50);
        if (empty($content)) {
            self::stop();
            throw new \Exception('HTTP server cannot be started');
        }
    }

    public static function stop(): void
    {
        if (isset(self::$pid)) {
            if (self::$pid > 0) {
                posix_kill(self::$pid, SIGTERM);
            }
            self::$pid = null;
        }
    }
}