<?php

namespace Mavik\Plugin\Content\Thumbnails\Extension;

defined('_JEXEC') or die;

class ImagesReplacer
{
    public function process(array $text)
    {
        foreach($text as &$textItemm) {
            $textItemm = 'TEST ' . $textItemm;
        }
        return $text;
    }
}