<?php

namespace Mavik\Plugin\Content\Thumbnails\Extension;

defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\SubscriberInterface;
use Joomla\CMS\Event\Content\ContentPrepareEvent;

/**
 * mavikThumbnails Plugin
 *
 * @copyright 2025 Vitalii Marenkov <admin@mavik.com.ua>
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 */
class Thumbnails extends CMSPlugin implements SubscriberInterface
{
    private ContextFactory $contextFactory;
    private ImagesReplacer $imagesReplacer;

    public function __construct($subject, $config)
    {
        parent::__construct($subject, $config);
        $this->contextFactory = new ContextFactory();
        $this->imagesReplacer = new ImagesReplacer();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'onContentPrepare' => 'onContentPrepare',
        ];
    }

    public function onContentPrepare(ContentPrepareEvent $event)
    {
        $context = $this->contextFactory->createContext($event->getContext());
        if (empty($context)) {
            return;
        }
        $item = $event->getItem();
        $text = $context->getText($item);
        $context->setText($item, $this->imagesReplacer->process($text));
    }
}