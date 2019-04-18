<?php

/**
 *
 * Copyright 2016 ELASTIC Consultants Inc.
 *
 */
use Cake\Database\Type;
use Elastic\TimeInterval\Database\Type\TimeIntervalType;

$getMap = method_exists(Type::class, 'getMap') ? 'getMap' : 'map';
if (!Type::$getMap('time_interval')) {
    Type::map('time_interval', TimeIntervalType::class);
}
