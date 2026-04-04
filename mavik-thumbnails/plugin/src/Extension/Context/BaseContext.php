<?php

namespace Mavik\Plugin\Content\Thumbnails\Extension\Context;

use Joomla\CMS\Form\Form;

class BaseContext implements ContextInterface
{
    /*
     * @return string[]
     */
    public function getText($item): array
    {
        return [$item->text];
    }

    /**
     * @param string[] $text
     */
    public function setText($item, array $text): void
    {
        $item->text = $text[0];
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