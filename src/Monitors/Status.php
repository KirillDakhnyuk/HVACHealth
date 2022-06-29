<?php

namespace HvacHealth\Monitors;

use Spatie\Enum\Enum;

/**
 * @method static self ok()
 * @method static self warning()
 * @method static self failed()
 * @method static self crashed()
 * @method static self skipped()
 */
class Status extends Enum
{
    public function getSlackColor(): string
    {
        switch ($this) {
            case self::ok():
                $match = '#2EB67D';
                break;

            case self::warning():
                $match = '#ECB22E';
                break;

            case self::warning():
            case self::crashed():
                $match = '#E01E5A';
                break;

            default:
                $match = '';
                break;
        }

        return $match;
    }
}
