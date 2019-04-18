<?php

namespace Elastic\TimeInterval\ValueObject;

use Cake\Chronos\Chronos;
use DateInterval;
use Exception;
use JsonSerializable;
use UnexpectedValueException;

/**
 * Class DateInterval
 */
class TimeInterval extends DateInterval implements JsonSerializable
{
    /**
     * Short time string parse as HH:MM
     *
     * if this flag to false, then parse as MM:SS
     *
     * @var bool
     */
    protected static $shortAsMinutes = true;

    /**
     * Formatter for to string convert.
     *
     * @var string
     */
    protected static $toStringFormat = '%r%H:%I:%S';

    /**
     * Formatter for to json convert.
     *
     * @var string
     */
    protected static $toJsonFormat = '%r%H:%I:%S';

    /**
     * {@inheritDoc}
     *
     * @throws UnexpectedValueException
     * @throws Exception
     */
    public static function createFromDateString($time)
    {
        $original = parent::createFromDateString($time);

        return static::createFromDateInterval($original);
    }

    /**
     * create from time string
     *
     * @param string $value '00:00:00'
     * @return TimeInterval
     * @throws UnexpectedValueException
     * @throws Exception
     */
    public static function createFromString($value)
    {
        if ($value === '') {
            $value = '00:00:00';
        }

        if (!preg_match('/\A(\-?)(\d+):(\d+)(?::(\d+))?\z/', $value, $matches)) {
            throw new UnexpectedValueException(sprintf('The value not match time format: %s', $value));
        }

        /** @noinspection PhpUnusedLocalVariableInspection */
        list($all, $minus, $hours, $minutes, $seconds) = array_pad($matches, 5, null);

        // parse as seconds
        if ($seconds === null && !static::$shortAsMinutes) {
            $seconds = $minutes;
            $minutes = $hours;
            $hours = 0;
        }

        $interval = new static(sprintf('PT%dH%dM%dS', abs($hours), $minutes, $seconds));
        $interval->invert = $minus === '-';

        return $interval;
    }

    /**
     * create from DateInterval
     *
     * @param DateInterval $value a DateInterval instance
     * @return TimeInterval
     * @throws UnexpectedValueException
     * @throws Exception
     */
    public static function createFromDateInterval(DateInterval $value)
    {
        $hours = $value->h + (static::getDays($value) * 24);
        $minutes = $value->i;
        $seconds = $value->s;

        return static::createFromString(
            sprintf(
                '%s%d:%d:%d',
                $value->invert ? '-' : '',
                $hours,
                $minutes,
                $seconds
            )
        );
    }

    /**
     * create from seconds
     *
     * @param int $seconds interval as seconds
     * @return TimeInterval
     * @throws UnexpectedValueException
     * @throws Exception
     */
    public static function createFromSeconds($seconds)
    {
        $startOfDay = Chronos::now()->startOfDay();
        $interval = $startOfDay->diff($startOfDay->addSeconds($seconds));

        return static::createFromDateInterval($interval);
    }

    /**
     * get days from DateInterval
     *
     * @param DateInterval $interval the DateInterval object
     * @return int
     */
    private static function getDays(DateInterval $interval)
    {
        if ($interval->days !== false) {
            return $interval->days;
        }

        if ($interval->m > 0 || $interval->y > 0) {
            throw new UnexpectedValueException('Can\'t parse DateInterval with years/months.');
        }

        return $interval->d;
    }

    /**
     * Short time string parse as HH:MM
     *
     * @var bool
     * @return void
     */
    public static function shortTimeAsMinutes()
    {
        static::$shortAsMinutes = true;
    }

    /**
     * Short time string parse as MM:SS
     *
     * @var bool
     * @return void
     */
    public static function shortTimeAsSeconds()
    {
        static::$shortAsMinutes = false;
    }

    /**
     * Serialized to String
     *
     * @return string
     */
    public function __toString()
    {
        return $this->format(static::$toStringFormat);
    }

    /**
     * Serialized to JSON
     *
     * @return string
     */
    public function jsonSerialize()
    {
        return $this->format(static::$toJsonFormat);
    }

    /**
     * Convert to seconds
     *
     * @return int
     */
    public function toSeconds()
    {
        $startOfDay = Chronos::now()->startOfDay();

        return $startOfDay->diffInSeconds($startOfDay->add($this), false);
    }
}
