<?php

namespace Mavik\Plugin\Content\Thumbnails\Extension;

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
}