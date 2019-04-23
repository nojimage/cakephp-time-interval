<?php

/**
 *
 * Copyright 2016 ELASTIC Consultants Inc.
 *
 */
use Cake\Database\Type;
use Cake\Validation\Validator;
use Elastic\TimeInterval\Database\Type\TimeIntervalType;
use Elastic\TimeInterval\Validation\TimeIntervalValidation;

$getMap = method_exists(Type::class, 'getMap') ? 'getMap' : 'map';
if (!Type::$getMap('time_interval')) {
    Type::map('time_interval', TimeIntervalType::class);
}

Validator::addDefaultProvider('timeInterval', TimeIntervalValidation::class);
