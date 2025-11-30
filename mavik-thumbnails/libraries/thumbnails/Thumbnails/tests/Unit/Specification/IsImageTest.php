<?php
declare(strict_types=1);

/*
 *  PHP Library for Image processing and creating thumbnails
 *  
 *  @package Mavik\Thumbnails
 *  @author Vitalii Marenkov <admin@mavik.com.ua>
 *  @copyright 2024 Vitalii Marenkov
 *  @license MIT; see LICENSE
 */

namespace Mavik\Thumbnails\Specification;

use PHPUnit\Framework\TestCase;
use Mavik\Thumbnails\Configuration;

class IsImageTest extends TestCase
{
    /** @var Configuration */
    private $configuration;

    public function __construct()
    {
        parent::__construct();
        $this->configuration = new Configuration(
            new Configuration\Server('', ''),
            new Configuration\Base()
        );
    }

    public function testTrue()
    {
        $dom = new \DOMDocument();
        $imgTag = $dom->createElement('img');
        $imgTag->setAttribute('src', 'test');        
        $isImage = new IsImage($this->configuration);
        $this->assertTrue($isImage($imgTag));
    }
    
    public function testFalse()
    {
        $isImage = new IsImage($this->configuration);        
        $dom = new \DOMDocument();
        
        $imgTag = $dom->createElement('img');                
        $this->assertFalse($isImage($imgTag));
        
        $divWithSrcTag = $dom->createElement('div');
        $divWithSrcTag->setAttribute('src', 'test');
        $this->assertFalse($isImage($divWithSrcTag));
    }
}
