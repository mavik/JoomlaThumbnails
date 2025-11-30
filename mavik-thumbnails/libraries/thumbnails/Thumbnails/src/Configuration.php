<?php
declare(strict_types=1);

/*
 *  PHP Library for Image processing and creating thumbnails
 *  
 *  @package Mavik\Image
 *  @author Vitalii Marenkov <admin@mavik.com.ua>
 *  @copyright 2021 Vitalii Marenkov
 *  @license GNU General Public License version 2 or later; see LICENSE
 */
namespace Mavik\Thumbnails;

use Mavik\Thumbnails\Configuration\Server;
use Mavik\Thumbnails\Configuration\Base;

class Configuration
{
    /** @var Server */
    private $server;

    /** @var Base */
    private $base;
    
    public function __construct(
        Server $server,
        Base $base
    ) {
        $this->server = $server;
        $this->base = $base;
    }
    
    public function server(): Server
    {
        return $this->server;
    }

    public function base(): Base
    {
        return $this->base;
    }
}
