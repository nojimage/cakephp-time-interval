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
class Plugin extends BasePlugin
{
    /**
     * Do bootstrapping or not
     *
     * @var bool
     */
    protected bool $bootstrapEnabled = true;

    /**
     * Enable middleware
     *
     * @var bool
     */
    protected bool $middlewareEnabled = false;

    /**
     * Load routes or not
     *
     * @var bool
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
        if (!TypeFactory::getMap('time_interval')) {
            TypeFactory::map('time_interval', TimeIntervalType::class);
            TypeFactory::map('time_interval_int', TimeIntervalAsIntType::class);
        }

        Validator::addDefaultProvider('timeInterval', new TimeIntervalValidation());
    }
}
