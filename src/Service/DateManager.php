<?php
/**
 * Created by PhpStorm.
 * User: eketchabepa
 * Date: 03.03.2019
 * Time: 03:06
 */

namespace App\Service;


class DateManager
{
    public function getDateFromTimezone(string $timezone)
    {
        $UTC = new \DateTimeZone("UTC");

        $newTz = new \DateTimeZone($timezone);
        $date = new \DateTime('now', $UTC);
        $date->setTimezone($newTz);

        return $date;
    }
}