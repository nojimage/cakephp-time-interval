<?php

namespace Elastic\TimeInterval\Test\TestCase\Database\Type;

use Cake\Database\Driver;
use Cake\Database\Driver\Mysql;
use Cake\I18n\FrozenTime;
use Cake\TestSuite\TestCase;
use Elastic\TimeInterval\Database\Type\TimeIntervalType;
use Elastic\TimeInterval\ValueObject\TimeInterval;
use PDO;

class TimeIntervalTypeTest extends TestCase
{
    /**
     * @var TimeIntervalType
     */
    private $type;

    /**
     * @var Driver|\PHPUnit_Framework_MockObject_MockObject
     */
    private $driver;

    public function setUp()
    {
        parent::setUp();
        $this->type = new TimeIntervalType();
        $this->driver = $this->getMockForAbstractClass(Driver::class);
    }

    public function tearDown()
    {
        unset($this->type, $this->driver);
        parent::tearDown();
    }

    /**
     * test convert DB to PHP
     *
     * @dataProvider dataToPHP
     */
    public function testToPHP($database, $expected)
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
    public function dataToPHP()
    {
        return [
            ['00:00:01', '00:00:01'],
            ['00:15:01', '00:15:01'],
            ['25:15:01', '25:15:01'],
            ['-25:15:01', '-25:15:01'],
        ];
    }

    /**
     * test convert null value to PHP
     */
    public function testToPHPWithNull()
    {
        $this->assertNull($this->type->toPHP(null, $this->driver));
    }

    /**
     * test convert value
     *
     * @dataProvider dataMarshal
     */
    public function testMarshal($value, $expected)
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
    public function dataMarshal()
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
            [new \DateInterval('PT25H15M1S'), '25:15:01'],
            [new \DateInterval('P1DT2H15M1S'), '26:15:01'],
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
    public function testMarshalWithNull()
    {
        $this->assertNull($this->type->marshal(null));
    }

    /**
     * test convert PHP to DB
     */
    public function testToDatabase()
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
    public function testToStatement()
    {
        $this->assertSame(PDO::PARAM_NULL, $this->type->toStatement(null, $this->driver));
        $this->assertSame(PDO::PARAM_STR, $this->type->toStatement(TimeInterval::createFromSeconds(1), $this->driver));
    }
}
