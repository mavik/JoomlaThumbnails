<?php

namespace Mavik\Plugin\Content\Thumbnails\Extension\Context;

use Mavik\Plugin\Content\Thumbnails\Extension\ContextInterface;

class Simple implements ContextInterface
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
}