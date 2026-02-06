<?php
/*
 * Copyright 2022 ELASTIC Consultants Inc.
 */
declare(strict_types=1);

namespace Elastic\TimeInterval;

use Cake\Core\BasePlugin;
use Cake\Core\PluginApplicationInterface;
use Cake\Database\TypeFactory;
use Cake\Validation\Validator;
use Elastic\TimeInterval\Database\Type\TimeIntervalAsIntType;
use Elastic\TimeInterval\Database\Type\TimeIntervalType;
use Elastic\TimeInterval\Validation\TimeIntervalValidation;

/**
 * Plugin class for CakePHP.
 */
class TimeIntervalPlugin extends BasePlugin
{
    /**
     * @inheritDoc
     */
    protected bool $bootstrapEnabled = true;

    /**
     * @inheritDoc
     */
    protected bool $middlewareEnabled = false;

    /**
     * @inheritDoc
     */
    protected bool $routesEnabled = false;

    /**
     * Console middleware
     *
     * @var bool
     */
    protected bool $consoleEnabled = false;

    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        if (!self::hasTimeIntervalTypeMap()) {
            TypeFactory::map('time_interval', TimeIntervalType::class);
            TypeFactory::map('time_interval_int', TimeIntervalAsIntType::class);
        }

        Validator::addDefaultProvider('timeInterval', new TimeIntervalValidation());
    }

    /**
     * Checks whether a time interval type map has been registered in the `TypeFactory`.
     *
     * @return bool True if the time interval type map exists, false otherwise.
     */
    private static function hasTimeIntervalTypeMap(): bool
    {
        // since CakePHP 5.3.0, TypeFactory::getMap() is deprecated
        if (method_exists(TypeFactory::class, 'getMapped')) {
            return (bool)TypeFactory::getMapped('time_interval');
        }

        return (bool)TypeFactory::getMap('time_interval');
    }
}
