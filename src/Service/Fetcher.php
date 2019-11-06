<?php
/**
 * Created by PhpStorm.
 * User: eketchabepa
 * Date: 03.03.2019
 * Time: 02:57
 */

namespace App\Service;


class Fetcher
{
    private $url = 'http://www.geoplugin.net/php.gp?ip=';

    public function getLocation(string $ip)
    {
        return unserialize(file_get_contents($this->url) . $ip);
    }
}