<?php
declare(strict_types=1);

/* 
 *  PHP Library for Image processing and creating thumbnails
 *  
 *  @package Mavik\Image
 *  @author Vitalii Marenkov <admin@mavik.com.ua>
 *  @copyright 2021 Vitalii Marenkov
 *  @license MIT; see LICENSE
 */

namespace Mavik\Image\Tests;

class CompareImages
{
    public static function distance(string $image1, string $image2): int
    {
        $image1 = realpath($image1);
        $image2 = realpath($image2);
        if (!$image1 || !$image2) {
            throw new \Exception("One of the images for comparison does not exist: '$image1' or '$image2'");
        }
        $cmd = "gm compare -matte -metric MSE \"{$image1}\" \"{$image2}\" 2>&1";
        exec($cmd, $output, $returnCode);

        if ($returnCode !== 0 && $returnCode !== 1) { // 1 is often used for difference in some tools, but let's check output
            $errorOutput = implode("\n", $output);
            throw new \Exception("GraphicsMagick compare failed with code $returnCode.\nCommand: {$cmd}\nOutput: {$errorOutput}");
        }

        foreach ($output as $line) {
            if (preg_match('/Total:\s+([0-9\.]+)\s+\d+\.\d+/', $line, $matches)) {
                return (int) round((float) $matches[1] * 100);
            }
        }
        throw new \Exception("Images '{$image1}' and '{$image2}' cannot be compared.\nCommand: {$cmd}\nOutput: " . implode("\n", $output));
    }
}