<?php
/*
 * Copyright 2022 ELASTIC Consultants Inc.
 */
declare(strict_types=1);

namespace Elastic\TimeInterval\Database\Type;

use DateInterval;
use Elastic\TimeInterval\ValueObject\TimeInterval;
use Exception;
use UnexpectedValueException;

trait TimeIntervalMarshalTrait
{
    /**
     * @param mixed $value the value
     * @return mixed
     * @throws UnexpectedValueException
     * @throws Exception
     */
    public function marshal($value): ?TimeInterval
    {
        if ($value === null || $value instanceof TimeInterval) {
            return $value;
        }

        if ($value instanceof DateInterval) {
            return TimeInterval::createFromDateInterval($value);
        }

        if (is_numeric($value)) {
            return TimeInterval::createFromSeconds($value);
        }

        if (is_string($value)) {
            return TimeInterval::createFromString($value);
        }

        throw new UnexpectedValueException('Invalid interval value.');
    }
}
