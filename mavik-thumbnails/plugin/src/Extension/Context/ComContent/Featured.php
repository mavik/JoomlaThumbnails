<?php

namespace Mavik\Plugin\Content\Thumbnails\Extension\Context\ComContent;

use Mavik\Plugin\Content\Thumbnails\Extension\Context\BaseContext;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Site\Helper\RouteHelper;
use Mavik\Plugin\Content\Thumbnails\Extension\Context\ComContent\Action\AddLink;
use Mavik\Thumbnails\Action\UseDefaultSize;
use Mavik\Thumbnails\Action\ReplaceToThumbnail;

class Featured extends BaseContext
{
    public function getActions(): array|null
    {
        $slug = isset($this->item->alias) ? $this->item->id . ':' . $this->item->alias : $this->item->id;
        $catid = $this->item->catid ?? 0;
        $language = $this->item->language ?? 0;

        $url = Route::_(RouteHelper::getArticleRoute($slug, $catid, $language));

        return [
            new UseDefaultSize($this->configuration),
            new ReplaceToThumbnail($this->configuration),
            new AddLink($this->configuration, $url),
        ];
    }
}