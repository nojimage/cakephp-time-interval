<?php
/*
 * Copyright 2022 ELASTIC Consultants Inc.
 */
declare(strict_types=1);

namespace Elastic\TimeInterval\Test\TestCase\Database\Type;

use Cake\Database\Driver;
use Cake\Database\Type;
use Cake\I18n\FrozenTime;
use Cake\TestSuite\TestCase;
use DateInterval;
use Elastic\TimeInterval\Database\Type\TimeIntervalAsIntType;
use Elastic\TimeInterval\Database\Type\TimeIntervalType;
use Elastic\TimeInterval\ValueObject\TimeInterval;

abstract class BaseTimeIntervalTypeTest extends TestCase
{
    /**
     * @var Type|TimeIntervalType|TimeIntervalAsIntType
     */
    protected $type;

    /**
     * @var Driver
     */
    protected $driver;

    public function tearDown(): void
    {
        unset($this->type, $this->driver);
        parent::tearDown();
    }

    /**
     * test convert DB to PHP
     *
     * @dataProvider dataToPHP
     */
    public function testToPHP($database, $expected): void
    {
        $result = $this->type->toPHP($database, $this->driver);
        $this->assertInstanceOf(TimeInterval::class, $result);
        $this->assertSame($expected, (string)$result);
    }

    /**
     * data for testToPHP
     *
     * @return array
     */
    abstract public function dataToPHP(): array;

    /**
     * test convert null value to PHP
     */
    public function testToPHPWithNull(): void
    {
        $this->assertNull($this->type->toPHP(null, $this->driver));
    }

    /**
     * test convert value
     *
     * @dataProvider dataMarshal
     */
    public function testMarshal($value, $expected): void
    {
        $result = $this->type->marshal($value);
        $this->assertInstanceOf(TimeInterval::class, $result);
        $this->assertSame($expected, (string)$result);
    }

    /**
     * data for testMarshal
     *
     * @return array
     */
    public function dataMarshal(): array
    {
        $a = new FrozenTime('2019-01-01 00:00:00');
        $b = new FrozenTime('2019-01-02 02:15:01');
        $c = new FrozenTime('2019-01-31 00:00:00');
        $d = new FrozenTime('2019-03-01 02:15:01');

        return [
            ['00:00:01', '00:00:01'],
            ['00:15:01', '00:15:01'],
            ['25:15:01', '25:15:01'],
            ['-25:15:01', '-25:15:01'],
            ['25:15', '25:15:00'],
            ['-25:15', '-25:15:00'],
            [new DateInterval('PT25H15M1S'), '25:15:01'],
            [new DateInterval('P1DT2H15M1S'), '26:15:01'],
            [$a->diff($b), '26:15:01'],
            [$c->diff($d), '698:15:01'],
            [0, '00:00:00'],
            [1, '00:00:01'],
            [-1, '-00:00:01'],
            [3599, '00:59:59'],
            [-3599, '-00:59:59'],
            [86401, '24:00:01'],
            [-86401, '-24:00:01'],
        ];
    }

    /**
     * test convert null value
     */
    public function testMarshalWithNull(): void
    {
        $this->assertNull($this->type->marshal(null));
    }

    /**
     * test convert PHP to DB
     */
    abstract public function testToDatabase();

    /**
     * test get statement
     */
    abstract public function testToStatement();
}
