<?php
/*
 * Copyright 2022 ELASTIC Consultants Inc.
 */
declare(strict_types=1);

namespace Elastic\TimeInterval\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * Class WorkTimesFixture
 */
class WorkTimesFixture extends TestFixture
{
    /**
     * fields property
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer'],
        'start' => ['type' => 'datetime', 'null' => false],
        'end' => ['type' => 'datetime', 'null' => false],
        'rest' => ['type' => 'time', 'null' => false, 'default' => '00:00:00'],
        'rest_seconds' => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'duration' => ['type' => 'time', 'null' => false, 'default' => '00:00:00'],
        '_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]],
    ];

    /**
     * records property
     *
     * @var array
     */
    public $records = [
        ['start' => '2019-04-01 10:00:00', 'end' => '2019-04-01 19:00:00', 'rest' => '01:00:00', 'rest_seconds' => 3600, 'duration' => '08:00:00'],
        ['start' => '2019-04-02 09:30:00', 'end' => '2019-04-02 12:30:00', 'rest' => '00:30:00', 'rest_seconds' => 1800, 'duration' => '02:30:00'],
        ['start' => '2019-04-03 09:45:00', 'end' => '2019-04-04 19:30:00', 'rest' => '08:00:00', 'rest_seconds' => 28800, 'duration' => '25:45:00'],
    ];
}
