<?php

namespace Mavik\Plugin\Content\Thumbnails\Extension\Context;

use Mavik\Thumbnails\Configuration;
use Joomla\CMS\Form\Form;

class BaseContext implements ContextInterface
{
    public function __construct(
        protected \stdClass $item,
        protected Configuration $configuration
    ) {
    }

    /*
     * @return string[]
     */
    public function getText(): array
    {
        return [$this->item->text];
    }

    /**
     * @param string[] $text
     */
    public function setText(array $text): void
    {
        $this->item->text = $text[0];
    }

    public function getActions(): ?array
    {
        return null;
    }

    public function addMenuItemConfig(Form $form, array $data): void
    {
        Form::addFormPath(JPATH_PLUGINS . '/content/mavik-thumbnails/forms');
        $form->loadFile('basic', false);
    }
}