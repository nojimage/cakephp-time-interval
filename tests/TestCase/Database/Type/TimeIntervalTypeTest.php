<?php
/*
 * Copyright 2022 ELASTIC Consultants Inc.
 */
declare(strict_types=1);

namespace Elastic\TimeInterval\Test\TestCase\Database\Type;

use Cake\Database\Driver;
use Elastic\TimeInterval\Database\Type\TimeIntervalType;
use Elastic\TimeInterval\ValueObject\TimeInterval;
use PDO;

class TimeIntervalTypeTest extends BaseTimeIntervalTypeTest
{
    public function setUp(): void
    {
        parent::setUp();
        $this->type = new TimeIntervalType();
        $this->driver = $this->getMockForAbstractClass(Driver::class);
    }

    /**
     * data for testToPHP
     *
     * @return array
     */
    public function dataToPHP(): array
    {
        return [
            ['00:00:01', '00:00:01'],
            ['00:15:01', '00:15:01'],
            ['25:15:01', '25:15:01'],
            ['-25:15:01', '-25:15:01'],
        ];
    }

    /**
     * test convert PHP to DB
     */
    public function testToDatabase(): void
    {
        $this->assertNull($this->type->toDatabase(null, $this->driver));
        $this->assertSame('00:00:01', $this->type->toDatabase(TimeInterval::createFromString('00:00:01'), $this->driver));
        $this->assertSame('00:00:01', $this->type->toDatabase(TimeInterval::createFromSeconds(1), $this->driver));
        $this->assertSame('24:00:00', $this->type->toDatabase(TimeInterval::createFromSeconds(86400), $this->driver));
        $this->assertSame('24:00:01', $this->type->toDatabase(TimeInterval::createFromSeconds(86401), $this->driver));
        $this->assertSame('00:00:00', $this->type->toDatabase(TimeInterval::createFromSeconds(0), $this->driver));
        $this->assertSame('-24:00:01', $this->type->toDatabase(TimeInterval::createFromSeconds(-86401), $this->driver));
    }

    /**
     * test get statement
     */
    public function testToStatement(): void
    {
        $this->assertSame(PDO::PARAM_NULL, $this->type->toStatement(null, $this->driver));
        $this->assertSame(PDO::PARAM_STR, $this->type->toStatement(TimeInterval::createFromSeconds(1), $this->driver));
    }
}
