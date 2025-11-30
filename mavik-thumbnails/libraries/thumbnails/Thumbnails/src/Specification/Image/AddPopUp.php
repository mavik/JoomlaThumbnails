<?php
declare(strict_types=1);

/*
 *  PHP Library for Image processing and creating thumbnails
 *  
 *  @package Mavik\Thumbnails
 *  @author Vitalii Marenkov <admin@mavik.com.ua>
 *  @copyright 2024 Vitalii Marenkov
 *  @license GNU General Public License version 2 or later; see LICENSE
 */

 namespace Mavik\Thumbnails\Specification\Image;

use Mavik\Thumbnails\Specification\CompositeSpecification;
use Mavik\Thumbnails\Configuration;

class AddPopUp extends CompositeSpecification
{   
    public function __construct(Configuration $configuration)
    {
        $hasSuitedClass = new HasSuitedClass($configuration);
        $isNotInsidePicture = (new IsInsidePicture())->not();
        $isZoomedOut = new IsZoomedOut();
        if ($configuration->base()->insideLinkAction() == Configuration\Base::INSIDE_LINK_ACTION_ALL) {
            $specification = $hasSuitedClass
                ->and($isNotInsidePicture)
                ->and($isZoomedOut)
            ;            
        } else {
            $isNotInsideLink = (new IsInsideLink($configuration))->not();
            $specification = $hasSuitedClass
                ->and($isNotInsideLink)
                ->and($isNotInsidePicture)
                ->and($isZoomedOut)
            ;
        }
        parent::__construct($specification);
    }
}
