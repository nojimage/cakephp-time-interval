<?php
declare(strict_types=1);

/**
 * Abstract schema for CakePHP tests.
 *
 * This format resembles the existing fixture schema
 * and is converted to SQL via the Schema generation
 * features of the Database package.
 */
return [
    'work_times' => [
        'table' => 'work_times',
        'columns' => [
            'id' => ['type' => 'integer'],
            'start' => ['type' => 'datetime', 'null' => false],
            'end' => ['type' => 'datetime', 'null' => false],
            'rest' => ['type' => 'time', 'null' => false, 'default' => '00:00:00'],
            'rest_seconds' => ['type' => 'integer', 'null' => false, 'default' => '0'],
            'duration' => ['type' => 'time', 'null' => false, 'default' => '00:00:00'],
        ],
        'constraints' => [
            'primary' => [
                'type' => 'primary',
                'columns' => [
                    'id',
                ],
            ],
        ],
    ],
];
