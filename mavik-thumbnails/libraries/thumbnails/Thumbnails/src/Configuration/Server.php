<?php
declare(strict_types=1);

/*
 *  PHP Library for Image processing and creating thumbnails
 *  
 *  @package Mavik\Image
 *  @author Vitalii Marenkov <admin@mavik.com.ua>
 *  @copyright 2021 Vitalii Marenkov
 *  @license GNU General Public License version 2 or later; see LICENSE
 */
namespace Mavik\Thumbnails\Configuration;

class Server
{
    /** @var string */
    private $baseUrl;
    
    /** @var string */
    private $webRootDir;

    /** @var string */
    private $thumbnailsDir;
    
    /** @var array */
    private $graphicLibraryPriority;

    public function __construct(
        string $baseUrl,
        string $webRootDir,
        string $thumbnailsDir,
        array $graphicLibraryPriority = null
    ) {
        $this->baseUrl = $baseUrl;
        $this->webRootDir = $webRootDir;
        $this->thumbnailsDir = $thumbnailsDir;
        $this->graphicLibraryPriority = $graphicLibraryPriority ?: [
            'gmagick',
            'imagick',
            'gd2'
        ];
    }
    
    public function baseUrl(): string
    {
        return $this->baseUrl;
    }

    public function webRootDir(): string
    {
        return $this->webRootDir;
    }

    public function thumbnailsDir(): string
    {
        return $this->thumbnailsDir;
    }
    
    public function graphicLibraryPriority(): array
    {
        return $this->graphicLibraryPriority;
    }
}
