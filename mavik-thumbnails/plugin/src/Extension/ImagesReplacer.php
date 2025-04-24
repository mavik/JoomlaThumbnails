<?php

namespace Mavik\Plugin\Content\Thumbnails\Extension;

defined('_JEXEC') or die;

use Mavik\Thumbnails\Thumbnails;
use Mavik\Thumbnails\JsAndCss;
use Mavik\Thumbnails\Configuration;
use Mavik\Thumbnails\Configuration\Base as ConfBase;
use Mavik\Thumbnails\Configuration\Server as ConfServer;
use Joomla\CMS\Uri\Uri;
use Joomla\Registry\Registry;
use Joomla\CMS\Factory;
use Joomla\CMS\Application\CMSWebApplicationInterface;

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
        $confBase = new ConfBase(
            $params->get('resizeMethod', 'fit'),
        );
        $config = new Configuration($confServer, $confBase);
        $this->thumbnails = new Thumbnails($config);
    }

    public function process(array $text)
    {
        $jsAndCss = new JsAndCss();
        foreach($text as &$textItem) {
            $result = ($this->thumbnails)($textItem);
            $textItem = $result->html;
            $jsAndCss->merge($result->jsAndCss);
        }
        $this->registerJsAndCss($jsAndCss);
        return $text;
    }

    private function registerJsAndCss(JsAndCss $jsAndCss): void
    {
        $app = Factory::getApplication();
        if (!$app instanceof CMSWebApplicationInterface) {
            return;
        }
        $wa = $app->getDocument()->getWebAssetManager();
        foreach ($jsAndCss->js() as $js) {
            $jsName = "lib_mavik_thumbnails.{$js}";
            $wa->registerAndUseScript($jsName, "media/lib_mavik_thumbnails/{$js}", ['version' => 'auto']);
        }
        $inlineJs = implode("\n", $jsAndCss->inlineJs());
        $wa->addInlineScript("
            document.addEventListener('DOMContentLoaded', function () {
                {$inlineJs}
            });
        ");
        foreach ($jsAndCss->css() as $css) {
            $cssName = "lib_mavik_thumbnails.{$css}";
            $wa->registerAndUseStyle($cssName, "media/lib_mavik_thumbnails/{$css}", ['version' => 'auto']);
        }
    }
}