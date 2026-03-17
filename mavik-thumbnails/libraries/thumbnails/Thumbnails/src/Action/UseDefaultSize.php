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

use Mavik\Thumbnails\Html\Image;
use Mavik\Thumbnails\Configuration;
use Mavik\Thumbnails\JsAndCss;
use Mavik\Thumbnails\Specification\AbstractSpecification;
use Mavik\Thumbnails\Specification\Image\UseDefaultSize as UseDefaultSizeSpecification;

class UseDefaultSize implements ActionInterface
{
    /**
     * @var Configuration
     */
    private $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Change $image and add JS and CSS to $jsAndCss.
     */
    public function execute(Image $image, JsAndCss $jsAndCss): void
    {
        $image->setSize(
            $this->configuration->base()->defaultWidth(),
            $this->configuration->base()->defaultHeight()
        );
    }

    public function specification(Configuration $configuration): AbstractSpecification
    {
        return new UseDefaultSizeSpecification($configuration);
    }
}