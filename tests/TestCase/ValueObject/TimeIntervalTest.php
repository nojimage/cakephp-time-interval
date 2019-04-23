<?php

namespace Elastic\TimeInterval\Test\TestCase\ValueObject;

use Cake\I18n\FrozenTime;
use Elastic\TimeInterval\ValueObject\TimeInterval;
use PHPUnit\Framework\TestCase;

/**
 * Test for TimeInterval
 */
class TimeIntervalTest extends TestCase
{
    public function testToString()
    {
        $this->assertSame('00:00:01', (string)(new TimeInterval('PT1S')));
        $this->assertSame('00:01:15', (string)(new TimeInterval('PT1M15S')));
        $this->assertSame('25:30:15', (string)(new TimeInterval('PT25H30M15S')));
        $this->assertSame('02:30:15', (string)(new TimeInterval('P1DT2H30M15S')));

        $negativeInterval = new TimeInterval('PT1M15S');
        $negativeInterval->invert = true;
        $this->assertSame('-00:01:15', (string)$negativeInterval);
    }

    public function testJsonSerialize()
    {
        $this->assertSame('"00:00:01"', json_encode(new TimeInterval('PT1S')));
        $this->assertSame('"00:01:15"', json_encode(new TimeInterval('PT1M15S')));
        $this->assertSame('"25:30:15"', json_encode(new TimeInterval('PT25H30M15S')));
        $this->assertSame('"02:30:15"', json_encode(new TimeInterval('P1DT2H30M15S')));

        $negativeInterval = new TimeInterval('PT1M15S');
        $negativeInterval->invert = true;
        $this->assertSame('"-00:01:15"', json_encode($negativeInterval));
    }

    public function testToSeconds()
    {
        $this->assertSame(1, (new TimeInterval('PT1S'))->toSeconds());
        $this->assertSame(75, (new TimeInterval('PT1M15S'))->toSeconds());
        $this->assertSame(91815, (new TimeInterval('PT25H30M15S'))->toSeconds());
        $negativeInterval = new TimeInterval('PT1M15S');
        $negativeInterval->invert = true;
        $this->assertSame(-75, $negativeInterval->toSeconds());
    }

    public function testCreateFromDateInterval()
    {
        $interval = TimeInterval::createFromDateInterval(new TimeInterval('P1DT2H30M15S'));
        $this->assertSame('26:30:15', (string)$interval);

        $a = new FrozenTime('2019-01-01 00:00:00');
        $b = new FrozenTime('2019-01-02 02:15:01');
        $interval = TimeInterval::createFromDateInterval($a->diff($b));
        $this->assertSame('26:15:01', (string)$interval);
        $interval = TimeInterval::createFromDateInterval($b->diff($a));
        $this->assertSame('-26:15:01', (string)$interval);
    }

    /**
     * @dataProvider dataCreateFromString
     * @param string $value the value
     * @param string $expected the expected value
     * @throws \Exception
     */
    public function testCreateFromString($value, $expected)
    {
        $interval = TimeInterval::createFromString($value);
        $this->assertSame($expected, (string)$interval);
    }

    /**
     * data for testCreateFromString
     *
     * @return array
     */
    public function dataCreateFromString()
    {
        return [
            ['00:00:01', '00:00:01'],
            ['00:15:01', '00:15:01'],
            ['25:15:01', '25:15:01'],
            ['-25:15:01', '-25:15:01'],
            ['', '00:00:00'], // empty string as 00:00:00
        ];
    }

    /**
     * @throws \Exception
     */
    public function testCreateFromStringWithShortTime()
    {
        // default parse as HH:MM
        $this->assertSame('00:00:00', (string)TimeInterval::createFromString('00:00'));
        $this->assertSame('01:15:00', (string)TimeInterval::createFromString('01:15'));
        $this->assertSame('26:30:00', (string)TimeInterval::createFromString('26:30'));
        $this->assertSame('-00:45:00', (string)TimeInterval::createFromString('-00:45'));

        TimeInterval::shortTimeAsSeconds();
        $this->assertSame('00:00:00', (string)TimeInterval::createFromString('00:00'));
        $this->assertSame('00:01:15', (string)TimeInterval::createFromString('01:15'));
        $this->assertSame('00:26:30', (string)TimeInterval::createFromString('26:30'));
        $this->assertSame('-00:00:45', (string)TimeInterval::createFromString('-00:45'));
    }

    /**
     * @dataProvider dataCreateFromSeconds
     * @param string $value the value
     * @param string $expected the expected value
     * @throws \Exception
     */
    public function testCreateFromSeconds($value, $expected)
    {
        $interval = TimeInterval::createFromSeconds($value);
        $this->assertSame($expected, (string)$interval);
        $this->assertSame($value, $interval->toSeconds());
    }

    /**
     * data for testCreateFromSeconds
     *
     * @return array
     */
    public function dataCreateFromSeconds()
    {
        return [
            [1, '00:00:01'],
            [75, '00:01:15'],
            [91815, '25:30:15'],
            [-1, '-00:00:01'],
            [-3599, '-00:59:59'],
            [-86401, '-24:00:01'],
        ];
    }

    public function testCanCompare()
    {
        $a = TimeInterval::createFromString('00:00:00');
        $b = TimeInterval::createFromSeconds(0);
        $c = TimeInterval::createFromString('00:00:01');
        $d = TimeInterval::createFromSeconds(1);

        $this->assertTrue($a == $b);
        $this->assertTrue($c == $d);
        $this->assertFalse($a == $c);
        $this->assertFalse($b == $d);
    }
}
