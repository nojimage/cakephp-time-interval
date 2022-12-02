<?php
/*
 * Copyright 2022 ELASTIC Consultants Inc.
 */
declare(strict_types=1);

namespace Elastic\TimeInterval\Database\Type;

use Cake\Database\Driver;
use Cake\Database\DriverInterface;
use Cake\Database\Type\BaseType;
use Elastic\TimeInterval\ValueObject\TimeInterval;
use Exception;
use PDO;
use UnexpectedValueException;

/**
 * TimeInterval custom type for INTEGER column
 *
 * @link http://book.cakephp.org/3.0/en/orm/database-basics.html#adding-custom-database-types
 */
class TimeIntervalAsIntType extends BaseType
{
    use TimeIntervalMarshalTrait;

    /**
     * @param mixed $value the value from database
     * @param Driver $driver db driver
     * @return mixed|null
     * @throws Exception
     */
    public function toPHP($value, DriverInterface $driver): ?TimeInterval
    {
        if ($value === null) {
            return null;
        }

        return TimeInterval::createFromSeconds((int)$value);
    }

    /**
     * @param mixed $value the value to database
     * @param Driver $driver db driver
     * @return false|mixed|string
     * @throws UnexpectedValueException
     * @throws Exception
     */
    public function toDatabase($value, DriverInterface $driver): ?int
    {
        if ($value === null) {
            return null;
        }

        if (!$value instanceof TimeInterval) {
            $value = $this->marshal($value);
        }

        return $value->toSeconds();
    }

    /**
     * @inheritDoc
     */
    public function toStatement($value, DriverInterface $driver): int
    {
        if ($value === null) {
            return PDO::PARAM_NULL;
        }

        return PDO::PARAM_INT;
    }
}
