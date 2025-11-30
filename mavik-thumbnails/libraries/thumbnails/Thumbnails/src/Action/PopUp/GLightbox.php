<?php
declare(strict_types=1);

/**
 * PHP Library for replacing images in html to thumbnails.
 *
 * @package Mavik\Thumbnails
 * @author Vitalii Marenkov <admin@mavik.com.ua>
 * @copyright 2023 Vitalii Marenkov
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Mavik\Thumbnails\Action\PopUp;

use Mavik\Thumbnails\Action\ActionInterface;
use Mavik\Thumbnails\Html\Image;
use Mavik\Thumbnails\Html\ImageWithLink;
use Mavik\Thumbnails\JsAndCss;

class GLightbox implements ActionInterface
{
    /**
     * Change $imageTag and add JS and CSS to $jsAndCss.
     */
    public function __invoke(Image $image, JsAndCss $jsAndCss): void
    {
        $imageWithLink = ImageWithLink::createFromImage($image);
        $imageWithLink->addLinkClass('glightbox');
        $imageWithLink->setLinkAttribute('data-gallery', 'gallery');
        $jsAndCss->addJs('glightbox/js/glightbox.js');
        $jsAndCss->addCss('glightbox/css/glightbox.css');
        $jsAndCss->addInlineJs('const lightbox = GLightbox();');
    }
}