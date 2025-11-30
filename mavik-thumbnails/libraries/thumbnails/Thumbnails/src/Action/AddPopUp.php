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

namespace Mavik\Thumbnails\Action;

use Mavik\Image\Image;
use Mavik\Thumbnails\Html\Image as ImageTag;
use Mavik\Thumbnails\JsAndCss;

class AddPopUp implements ActionInterface
{
    private ActionInterface $library;

    public function __construct(string $library)
    {
        $this->library = new PopUp\GLightbox();
    }

    /**
     * Change $imageTag and add JS and CSS to $jsAndCss.
     */
    public function __invoke(ImageTag $imageTag, JsAndCss $jsAndCss, Image $image): void
    {
        $this->library->__invoke($imageTag, $jsAndCss, $image);
    }
}