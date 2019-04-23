<?php

namespace Elastic\TimeInterval\Validation;

use Cake\Validation\Validation;
use DateInterval;

class TimeIntervalValidation extends Validation
{
    /**
     * Time validation, determines if the string passed is a valid time.
     *
     * @param int|string|array|DateInterval $check a valid time string/object
     * @param bool $allowNegative allow negative interval
     * @return bool Success
     */
    public static function timeInterval($check, $allowNegative = true)
    {
        if ($check instanceof DateInterval) {
            return $allowNegative || !$check->invert;
        }

        if (Validation::isInteger($check)) {
            return $allowNegative || 0 <= $check;
        }

        if (is_array($check)) {
            $check = static::_getDateString($check);
        }

        $regex = $allowNegative ? '%^\-?(\d+)(:[0-5]\d){1,2}$%' : '%^(\d+)(:[0-5]\d){1,2}$%';

        return static::_check($check, $regex);
    }
}
