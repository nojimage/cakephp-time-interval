<?php

namespace Elastic\TimeInterval\Database\Type;

use Cake\Database\Driver;
use Cake\Database\Type;
use DateInterval;
use Elastic\TimeInterval\ValueObject\TimeInterval;
use Exception;
use UnexpectedValueException;

/**
 * TimeInterval custom type for MySQL's TIME column
 *
 * @link http://book.cakephp.org/3.0/en/orm/database-basics.html#adding-custom-database-types
 */
class TimeIntervalType extends Type
{
    use TimeIntervalMarshalTrait;

    /**
     * @param mixed $value the value from database
     * @param Driver $driver db driver
     * @return mixed|null
     * @throws Exception
     */
    public function toPHP($value, Driver $driver)
    {
        if ($value === null) {
            return null;
        }

        return TimeInterval::createFromString($value);
    }

    /**
     * @param mixed $value the value to database
     * @param Driver $driver db driver
     * @return false|mixed|string
     * @throws UnexpectedValueException
     * @throws Exception
     */
    public function toDatabase($value, Driver $driver)
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
