<?php
declare(strict_types=1);

/**
 * PHP Library for Image processing and creating thumbnails
 *
 * @package Mavik\Image
 * @author Vitalii Marenkov <admin@mavik.com.ua>
 * @copyright 2021 Vitalii Marenkov
 * @license GNU General Public License version 2 or later; see LICENSE
 */

namespace Mavik\Image;

use Mavik\Image\Exception\FileException;
use Mavik\Image\FileName;

class ImageFile
{
    /** @var string */
    private $path;
    
    /** @var string */
    private $url;

    /** @var int */
    private $fileSize;
    
    /** @var ImageSize **/
    private $imageSize;    
    
    /**
     * IMAGETYPE_XXX
     * 
     * @var int
     */
    private $type;
    
    public function __construct(FileName $fileName)
    {
        $this->path = $fileName->getPath();
        $this->url = $fileName->getUrl();
    }
    
    public function getPath(): ?string
    {
        return $this->path;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getFileSize(): int
    {
        if (!isset($this->fileSize)) {
            $this->initFileSize();
        }       
        return $this->fileSize;
    }
    
    /**
     * @return int IMAGETYPE_XXX
     */
    public function getType(): int
    {
        if (!isset($this->type)) {
            $this->initImageInfo();
        }
        return $this->type;
    }
    
    public function getImageSize(): ImageSize
    {
        if (!isset($this->imageSize)) {
            $this->initImageInfo();
        }
        return $this->imageSize;
    }

    private function initImageInfo(): void
    {
        if ($this->path) {
            $this->initImageInfoFromPath();
        } else {
            $this->initImageInfoFromUrl();
        }
    }
    
    private function initImageInfoFromPath(): void
    {
        $imageInfo = getimagesize($this->path);
        $this->type = $imageInfo[2];
        $this->imageSize = new ImageSize($imageInfo[0], $imageInfo[1]);
    }

    private function initFileSize(): void
    {
        if ($this->path) {
            $this->initFileSizeFromPath();
        } else {
            $this->initImageInfoFromUrl();
        }
    }

    private function initFileSizeFromPath(): void
    {
        $this->fileSize = filesize($this->path);
        if ($this->fileSize === false) {
            throw new FileException();
        }        
    }
    
    /**
     * Set type, width, height and size of file
     * 
     * @return void
     * @throws FileException
     */
    private function initImageInfoFromUrl(): void
    {
        $context = stream_context_create([
            'http' => [
                'header' => "Range: bytes=0-65536\r\n"
                          . "User-Agent: mavikImage/1.0",
            ]
        ]);        
        $imageData = @file_get_contents($this->url, false, $context, 0, 65536);
        if ($imageData === false) {
            throw new FileException("Can't open URL \"{$this->url}\"");
        }        
        // The special var $http_response_header is setted by PHP in file_get_contents()
        $httpHeaders = $this->parseHttpHeaders($http_response_header);
        $this->fileSize = $this->fileSizeFromHttpHeaders($httpHeaders);
        if (!isset($this->fileSize)) {
            $imageData = file_get_contents($this->url);
            $this->fileSize = strlen($imageData);
        }
        $imageSize = getimagesizefromstring($imageData);
        if (!isset($imageSize[0]) || !isset($imageSize[1]) || !isset($imageSize[2])) {
            throw new FileException("Can't get size or type of image \"{$this->url}\"");
        }
        $this->imageSize = new ImageSize($imageSize[0], $imageSize[1]);
        $this->type = $imageSize[2];
    }
    
    private function fileSizeFromHttpHeaders(array $httpHeaders = null): ?int
    {        
        if (!isset($httpHeaders['response_code'])) {
            return null;
        }
        if (
            $httpHeaders['response_code'] == 206 &&
            isset($httpHeaders['content-range']) &&
            strpos($httpHeaders['content-range'], 'bytes') !== false
        ) {
            $parts = explode('/', $httpHeaders['content-range']);
            return (int)$parts[1] ?? null;            
        }
        if (
            $httpHeaders['response_code'] == 200 &&
            isset($httpHeaders['content-length']) &&
            is_numeric($httpHeaders['content-length'])
        ) {
            return (int)$httpHeaders['content-length'];
        }
        return null;
    }

    private function parseHttpHeaders(array $httpHeaders = null): array
    {
        $result = [];
        if (!is_array($httpHeaders)) {
            return $result;
        }
        foreach ($httpHeaders as $line) {
            $parts = explode(':', $line, 2);
            if (isset($parts[1])) {
                $result[strtolower(trim($parts[0]))] = trim($parts[1]);
            } else {
                $result[] = $line;
                if (preg_match('#HTTP/[0-9\.]+\s+([0-9]+)#',$line, $matches)) {
                    $result['response_code'] = intval($matches[1]);
                }
            }
        }
        return $result;
    }    
}
