<?php

namespace Elastic\TimeInterval\Test\TestCase\Validation;

use Cake\TestSuite\TestCase;
use DateInterval;
use DateTime;
use DateTimeImmutable;
use Elastic\TimeInterval\Validation\TimeIntervalValidation;
use Elastic\TimeInterval\ValueObject\TimeInterval;
use stdClass;

/**
 * Test for TimeIntervalValidation
 */
class TimeIntervalValidationTest extends TestCase
{
    /**
     * test timeInterval method
     *
     * @return void
     */
    public function testTimeInterval()
    {
        // long time format
        $this->assertTrue(TimeIntervalValidation::timeInterval('00:00:00'));
        $this->assertTrue(TimeIntervalValidation::timeInterval('23:59:59'));
        $this->assertTrue(TimeIntervalValidation::timeInterval('1:00:00'));
        $this->assertTrue(TimeIntervalValidation::timeInterval('-23:59:59'));
        $this->assertFalse(TimeIntervalValidation::timeInterval('23:59:60'));
        $this->assertFalse(TimeIntervalValidation::timeInterval('23:60:59'));
        // short time format
        $this->assertTrue(TimeIntervalValidation::timeInterval('00:00'));
        $this->assertTrue(TimeIntervalValidation::timeInterval('23:59'));
        $this->assertTrue(TimeIntervalValidation::timeInterval('24:00'));
        $this->assertTrue(TimeIntervalValidation::timeInterval('1:00'));
        $this->assertTrue(TimeIntervalValidation::timeInterval('-23:59'));
        $this->assertFalse(TimeIntervalValidation::timeInterval('23:60'));
        // integer
        $this->assertTrue(TimeIntervalValidation::timeInterval('0'));
        $this->assertTrue(TimeIntervalValidation::timeInterval('123'));
        $this->assertTrue(TimeIntervalValidation::timeInterval('-456'));
        $this->assertTrue(TimeIntervalValidation::timeInterval(0));
        $this->assertTrue(TimeIntervalValidation::timeInterval(123));
        $this->assertTrue(TimeIntervalValidation::timeInterval(-456));
        $this->assertFalse(TimeIntervalValidation::timeInterval('1.0'));
        $this->assertFalse(TimeIntervalValidation::timeInterval(1.0));
        // with meridian
        $this->assertFalse(TimeIntervalValidation::timeInterval('1pm'));
        $this->assertFalse(TimeIntervalValidation::timeInterval('1 pm'));
        $this->assertFalse(TimeIntervalValidation::timeInterval('1 PM'));
        $this->assertFalse(TimeIntervalValidation::timeInterval('1:00pm'));
        $this->assertFalse(TimeIntervalValidation::timeInterval('12:01am'));
        $this->assertFalse(TimeIntervalValidation::timeInterval('12:01pm'));
        $this->assertFalse(TimeIntervalValidation::timeInterval('13:00pm'));
    }

    /**
     * test timeInterval validation when passing an array
     *
     * @return void
     */
    public function testTimeArray()
    {
        $date = ['hour' => 13, 'minute' => 14, 'second' => 15];
        $this->assertTrue(TimeIntervalValidation::timeInterval($date));
        $date = [
            'hour' => 'farts', 'minute' => 'farts',
        ];
        $this->assertFalse(TimeIntervalValidation::timeInterval($date));
        $date = [];
        $this->assertFalse(TimeIntervalValidation::timeInterval($date));
    }

    /**
     * test timeInterval validation when passing an object
     *
     * @return void
     */
    public function testTimeIntervalObject()
    {
        $interval = new DateInterval('PT1H');
        $this->assertTrue(TimeIntervalValidation::timeInterval($interval));
        $negativeInterval = new DateInterval('PT1H');
        $negativeInterval->invert = 1;
        $this->assertTrue(TimeIntervalValidation::timeInterval($negativeInterval));
        $this->assertTrue(TimeIntervalValidation::timeInterval(TimeInterval::createFromString('00:00:00')));
        $this->assertTrue(TimeIntervalValidation::timeInterval(TimeInterval::createFromString('01:00:00')));
        $this->assertTrue(TimeIntervalValidation::timeInterval(TimeInterval::createFromString('-01:00:00')));
        $this->assertFalse(TimeIntervalValidation::timeInterval(new DateTime()));
        $this->assertFalse(TimeIntervalValidation::timeInterval(new DateTimeImmutable()));
        $this->assertFalse(TimeIntervalValidation::timeInterval(new stdClass()));
    }

    /**
     * test timeInterval method with allowNegative is false
     *
     * @return void
     */
    public function testTimeIntervalNotAllowNegative()
    {
        $this->assertTrue(TimeIntervalValidation::timeInterval('00:00:00', false));
        $this->assertTrue(TimeIntervalValidation::timeInterval('23:59:59', false));
        $this->assertFalse(TimeIntervalValidation::timeInterval('-23:59:59', false));
        $this->assertTrue(TimeIntervalValidation::timeInterval('00:00', false));
        $this->assertTrue(TimeIntervalValidation::timeInterval('23:59', false));
        $this->assertFalse(TimeIntervalValidation::timeInterval('-23:59', false));
        $this->assertTrue(TimeIntervalValidation::timeInterval('0', false));
        $this->assertTrue(TimeIntervalValidation::timeInterval('123', false));
        $this->assertFalse(TimeIntervalValidation::timeInterval('-456', false));
        $this->assertTrue(TimeIntervalValidation::timeInterval(0, false));
        $this->assertTrue(TimeIntervalValidation::timeInterval(123, false));
        $this->assertFalse(TimeIntervalValidation::timeInterval(-456, false));
        $interval = new DateInterval('PT1H');
        $this->assertTrue(TimeIntervalValidation::timeInterval($interval, false));
        $negativeInterval = new DateInterval('PT1H');
        $negativeInterval->invert = 1;
        $this->assertFalse(TimeIntervalValidation::timeInterval($negativeInterval, false));
        $this->assertTrue(TimeIntervalValidation::timeInterval(TimeInterval::createFromString('00:00:00'), false));
        $this->assertTrue(TimeIntervalValidation::timeInterval(TimeInterval::createFromString('01:00:00'), false));
        $this->assertFalse(TimeIntervalValidation::timeInterval(TimeInterval::createFromString('-01:00:00'), false));
    }
}
