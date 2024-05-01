<?php
/*
 * Copyright 2022 ELASTIC Consultants Inc.
 */
declare(strict_types=1);

namespace Elastic\TimeInterval\Database\Type;

use Cake\Database\Driver;
use Cake\Database\Type\BaseType;
use Elastic\TimeInterval\ValueObject\TimeInterval;
use Exception;

/**
 * TimeInterval custom type for MySQL's TIME column
 *
 * @link http://book.cakephp.org/3.0/en/orm/database-basics.html#adding-custom-database-types
 */
class TimeIntervalType extends BaseType
{
    use TimeIntervalMarshalTrait;

    /**
     * @param mixed $value the value from database
     * @param Driver $driver db driver
     * @return TimeInterval|null
     * @throws Exception
     */
    public function toPHP(mixed $value, Driver $driver): ?TimeInterval
    {
        if ($value === null) {
            return null;
        }

        return TimeInterval::createFromString($value);
    }

    /**
     * @param mixed $value the value to database
     * @param Driver $driver db driver
     * @return string|null
     * @throws Exception
     */
    public function toDatabase(mixed $value, Driver $driver): ?string
    {
        if ($value === null) {
            return null;
        }

        if (!$value instanceof TimeInterval) {
            $value = $this->marshal($value);
        }

        return (string)$value;
    }
}
