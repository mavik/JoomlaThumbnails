<?php

namespace Mavik\Plugin\Content\Thumbnails\Extension\Context;

use Mavik\Thumbnails\Configuration;
use Mavik\Thumbnails\Action\ActionInterface;
use Joomla\CMS\Form\Form;

interface ContextInterface
{

    public function __construct(\stdClass $item, Configuration $configuration);

    /**
     * @return string[]
     */
    public function getText(): array;

    /**
     * @param string[] $text
     */
    public function setText(array $text): void;

    /**
     * @return ActionInterface[]|null
     */
    public function getActions(): ?array;

    public function addMenuItemConfig(Form $form, array $data): void;
}