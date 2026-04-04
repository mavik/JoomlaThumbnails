<?php

namespace Mavik\Plugin\Content\Thumbnails\Extension\Context;

use Mavik\Thumbnails\Action\ActionInterface;
use Joomla\CMS\Form\Form;

interface ContextInterface
{
    /**
     * @return string[]
     */
    public function getText($item): array;

    /**
     * @param string[] $text
     */
    public function setText($item, array $text): void;

    /**
     * @return ActionInterface[]|null
     */
    public function getActions(): ?array;

    public function addMenuItemConfig(Form $form, array $data): void;
}