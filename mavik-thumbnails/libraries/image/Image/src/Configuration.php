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
namespace Mavik\Image;

use Mavik\Image\Exception\FileException;

class Configuration
{
    /** @var string */
    private $baseUri;
    
    /** @var string */
    private $webRootDirectory;

    /** @var string */
    private $thumbnailsDirectory;
    
    /** @var GraphicLibraryInterface */
    private $graphicLibrary;

    /**
     * @param string $baseUri
     * @param string $webRootDirectory Absolute path to the web root directory.
     * @param string $thumbnailsDirectory Relative path to the thumbnails directory.
     * @param string[] $graphicLibraryPriority
     */
    public function __construct(
        string $baseUri,
        string $webRootDirectory,
        string $thumbnailsDirectory,
        array $graphicLibraryPriority = ['gmagick', 'imagick', 'gd2']
    ) {
        $this->setBaseUri($baseUri);
        $this->setWebRootDirectory($webRootDirectory);
        $this->setThumbnailsDirectory($webRootDirectory, $thumbnailsDirectory);
        $this->initGraphicLibrary($graphicLibraryPriority);
    }
    
    public function baseUri(): string
    {
        return $this->baseUri;
    }

    public function webRootDirectory(): string
    {
        return $this->webRootDirectory;
    }

    public function thumbnailsDirectory(): string
    {
        return $this->thumbnailsDirectory;
    }

    public function graphicLibrary(): GraphicLibraryInterface
    {
        return $this->graphicLibrary;
    }
    
    private function setBaseUri(string $baseUri): void
    {
        $baseUri = trim($baseUri);
        if (empty($baseUri)) {
            throw new FileException("Configuration base_url can't be empty.");
        }
        if (substr($baseUri, -1) !== '/') {
            $baseUri .= '/';
        }
        $this->baseUri = $baseUri;
    }

    private function setWebRootDirectory(string $webRootDirectory): void
    {
        $path = realpath( $webRootDirectory);
        if ($path === false) {
            throw new \InvalidArgumentException("Directory '{webRootDirectory}' does not exist.");
        }
        if (substr($path, -1) !== '/') {
            $path .= '/';
        }
        $this->webRootDirectory = $path;
    }

    private function setThumbnailsDirectory(string $webRoorDirecory, string $thumbnailsDirectory): void
    {
        $thumbnailsFullPath =  $webRoorDirecory . '/' . $thumbnailsDirectory;
        if (!file_exists($thumbnailsFullPath)) {
            mkdir($thumbnailsFullPath, 0755, true);
        }        
        $path = realpath($thumbnailsFullPath);
        if ($path === false) {
            throw new \InvalidArgumentException("Directory '{$thumbnailsFullPath}' does not exist.");
        }
        if (substr($path, -1) !== '/') {
            $path .= '/';
        }
        $this->thumbnailsDirectory = $path;
    }

    /**
     * @param string[] $graphicLibraryPriority
     */
    private function initGraphicLibrary(array $graphicLibraryPriority)
    {
        foreach ($graphicLibraryPriority as $graphicLibrary) {
            $className = 'Mavik\\Image\\GraphicLibrary\\' . ucfirst(strtolower($graphicLibrary));
            if (class_exists($className, true) && $className::isInstalled()) {
                $graphicLibraryClass = $className;
                break;
            }
        }
        if (!isset($graphicLibraryClass)) {
            throw new Exception\ConfigurationException('Configuration error: None of the required graphics libraries are installed.');
        }
        $this->graphicLibrary = new $graphicLibraryClass;
    }
}
