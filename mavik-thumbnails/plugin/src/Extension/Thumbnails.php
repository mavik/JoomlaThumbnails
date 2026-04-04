<?php

namespace Mavik\Plugin\Content\Thumbnails\Extension;

defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\SubscriberInterface;
use Joomla\CMS\Event\Content\ContentPrepareEvent;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Event\Model\PrepareFormEvent;
use Mavik\Plugin\Content\Thumbnails\Extension\Context\BaseContext;

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

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->contextFactory = new ContextFactory();
        $this->imagesReplacer = new ImagesReplacer($this->params);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'onContentPrepare' => 'onContentPrepare',
            'onContentPrepareForm' => 'onContentPrepareForm',
        ];
    }

    public function onContentPrepare(ContentPrepareEvent $event)
    {
        if ($this->getApplication()->isClient('administrator')) {
            return;
        }
        $context = new BaseContext();
        $item = $event->getItem();
        $text = $context->getText($item);
        $context->setText($item, $this->imagesReplacer->execute($text));
    }

    public function onContentPrepareForm(PrepareFormEvent $formEvent): bool
    {
        $form = $formEvent->getForm();
        $data = (array) $formEvent->getData();

        switch (true) {
            case $form->getName() === 'com_plugins.plugin' && $data['name'] === "PLG_CONTENT_MAVIK_THUMBNAILS":
                $this->loadConfigForm($form);
                return true;
            case $form->getName() === 'com_menus.item':
                $this->addMenuItemConfig($form, $data);
                return true;
        }

        return true;
    }

    private function loadConfigForm(Form $form): void
    {
        Form::addFormPath(JPATH_PLUGINS . '/content/mavik-thumbnails/forms');
        $form->loadFile('basic', false);
        $form->loadFile('system', false);
    }

    private function addMenuItemConfig(Form $form, array $data): void
    {
        $this->loadLanguage();

        $itemType = str_replace('_', '', ucwords($data['type'] ?? '', '_'));
        $itemOption = str_replace('_', '', ucwords($data['params']['option'] ?? '', '_'));
        $itemView = str_replace('_', '', ucwords($data['params']['view'] ?? '', '_'));

        $contextClass = "\\Mavik\\Plugin\\Content\\Thumbnails\\Extension\\Context\\{$itemType}\\{$itemOption}\\{$itemView}";
        $context = new $contextClass();
        $context->addMenuItemConfig($form, $data);
    }
}