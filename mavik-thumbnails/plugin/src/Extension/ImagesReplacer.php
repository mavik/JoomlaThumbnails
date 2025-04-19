<?php

namespace Mavik\Plugin\Content\Thumbnails\Extension;

defined('_JEXEC') or die;

use Mavik\Thumbnails\Thumbnails;
use Mavik\Thumbnails\Configuration;
use Mavik\Thumbnails\Configuration\Base as ConfBase;
use Mavik\Thumbnails\Configuration\Server as ConfServer;
use Joomla\CMS\Uri\Uri;
use Joomla\Registry\Registry;

class ImagesReplacer
{
    /** @var Registry */
    private $registry;

    /** @var Thumbnails */
    private $thumbnails;

    public function __construct(Registry $params)
    {
        $confServer = new ConfServer(
            Uri::base(),
            JPATH_ROOT,
            $params->get('thumbnailsDir', 'images/thumbnails'),
        );
        $confBase = new ConfBase();
        $config = new Configuration($confServer, $confBase);
        $this->thumbnails = new Thumbnails($config);
    }

    public function process(array $text)
    {
        foreach($text as &$textItemm) {
            $textItemm = ($this->thumbnails)($textItemm)->html;
        }
        return $text;
    }
}