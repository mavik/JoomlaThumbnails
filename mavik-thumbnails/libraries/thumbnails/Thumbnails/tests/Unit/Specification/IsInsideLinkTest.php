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
use Mavik\Thumbnails\Specification\DOMElement\IsInsideLink;

class IsInsideLinkTest extends TestCase
{
    public function testTrue()
    {
        $dom = new \DOMDocument();

        $aTag = $dom->createElement('a');
        $aTag->setAttribute('href', 'test');
        $dom->appendChild($aTag);

        $pTag = $dom->createElement('p');
        $aTag->appendChild($pTag);

        $imgTag = $dom->createElement('img');
        $pTag->appendChild($imgTag);

        $isInsideLink = new IsInsideLink();
        $this->assertTrue($isInsideLink->isSatisfiedBy($imgTag));
    }

    public function testFalse()
    {
        $dom = new \DOMDocument();

        $aTagWithoutHref = $dom->createElement('a');
        $dom->appendChild($aTagWithoutHref);

        $divWithHref = $dom->createElement('div');
        $divWithHref->setAttribute('href', 'test');
        $aTagWithoutHref->appendChild($divWithHref);

        $imgTag = $dom->createElement('img');
        $divWithHref->appendChild($imgTag);

        $isInsideLink = new IsInsideLink();
        $this->assertFalse($isInsideLink->isSatisfiedBy($imgTag));
    }
}
