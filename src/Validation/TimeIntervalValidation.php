<?php
/*
 * Copyright 2022 ELASTIC Consultants Inc.
 */
declare(strict_types=1);

namespace Elastic\TimeInterval\Validation;

use Cake\Validation\Validation;
use DateInterval;

class TimeIntervalValidation extends Validation
{
    /**
     * Time validation, determines if the string passed is a valid time.
     *
     * @param int|string|array|\DateInterval $check a valid time string/object
     * @param array|bool $allowNegative allow negative interval
     * @return bool Success
     */
    public static function timeInterval($check, $allowNegative = true): bool
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

        $regex = $allowNegative ? '%^\-?(\d+)(:[0-5]\d){1,2}(\.\d{6})?$%' : '%^(\d+)(:[0-5]\d){1,2}(\.\d{6})?$%';

        return static::_check($check, $regex);
    }
}
