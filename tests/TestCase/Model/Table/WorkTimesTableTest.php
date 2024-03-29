<?php
/*
 * Copyright 2022 ELASTIC Consultants Inc.
 */
declare(strict_types=1);

namespace Elastic\TimeInterval\Test\TestCase\Model\Table;

use Cake\I18n\FrozenTime;
use Cake\TestSuite\TestCase;
use Elastic\TimeInterval\ValueObject\TimeInterval;
use TestApp\Model\Table\WorkTimesTable;

class WorkTimesTableTest extends TestCase
{
    public $fixtures = [
        'plugin.Elastic/TimeInterval.WorkTimes',
    ];

    /**
     * @var WorkTimesTable
     */
    private $table;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadPlugins(['Elastic/TimeInterval']);

        $this->table = $this->getTableLocator()->get('WorkTimes');
    }

    public function tearDown(): void
    {
        unset($this->table);
        parent::tearDown();
    }

    public function testGetFromDb(): void
    {
        $record = $this->table->get(1);
        $this->assertInstanceOf(TimeInterval::class, $record->rest);
        $this->assertSame('01:00:00', (string)$record->rest);

        $this->assertInstanceOf(TimeInterval::class, $record->rest_seconds);
        $this->assertSame('01:00:00', (string)$record->rest_seconds);

        $this->assertInstanceOf(TimeInterval::class, $record->duration);
        $this->assertSame('08:00:00', (string)$record->duration);
    }

    public function testMutation(): void
    {
        // set with time string
        $recordFromTimeString = $this->table->newEntity([
            'rest' => '00:15:01',
        ]);
        $this->assertInstanceOf(TimeInterval::class, $recordFromTimeString->rest);
        $this->assertSame('00:15:01', (string)$recordFromTimeString->rest);

        // set with DateInterval object
        $now = FrozenTime::now();
        $recordFromDateInterval = $this->table->newEntity([
            'rest' => $now->diff($now->addDays(2)->addHour()->addMinutes(2)->addSeconds(3)),
        ]);
        $this->assertInstanceOf(TimeInterval::class, $recordFromDateInterval->rest);
        $this->assertSame('49:02:03', (string)$recordFromDateInterval->rest);

        // set with seconds
        $recordFromSeconds = $this->table->newEntity([
            'rest' => 7200 + 180 + 4,
        ]);
        $this->assertInstanceOf(TimeInterval::class, $recordFromSeconds->rest);
        $this->assertSame('02:03:04', (string)$recordFromSeconds->rest);
    }

    public function testEntityMutation(): void
    {
        // set with time string
        $recordFromTimeString = $this->table->newEntity([]);
        $recordFromTimeString->duration = '00:15:01';
        $this->assertInstanceOf(TimeInterval::class, $recordFromTimeString->duration);
        $this->assertSame('00:15:01', (string)$recordFromTimeString->duration);

        // set with DateInterval object
        $now = FrozenTime::now();
        $recordFromDateInterval = $this->table->newEntity([]);
        $recordFromDateInterval->duration = $now->diff($now->addDays(2)->addHour()->addMinutes(2)->addSeconds(3));
        $this->assertInstanceOf(TimeInterval::class, $recordFromDateInterval->duration);
        $this->assertSame('49:02:03', (string)$recordFromDateInterval->duration);

        // set with seconds
        $recordFromSeconds = $this->table->newEntity([]);
        $recordFromSeconds->duration = 7200 + 180 + 4;
        $this->assertInstanceOf(TimeInterval::class, $recordFromSeconds->duration);
        $this->assertSame('02:03:04', (string)$recordFromSeconds->duration);
    }

    public function testSaveAs(): void
    {
        $record = $this->table->get(1);

        // set with time string and save
        $record->rest = '00:15:01';
        $this->assertNotFalse($this->table->save($record));
        $recordFromTimeString = $this->table->get($record->id);
        $this->assertInstanceOf(TimeInterval::class, $recordFromTimeString->rest);
        $this->assertSame('00:15:01', (string)$recordFromTimeString->rest);

        // set with DateInterval object and save
        $now = FrozenTime::now();
        $record->rest = $now->diff($now->addDays(2)->addHour()->addMinutes(2)->addSeconds(3));
        $this->assertNotFalse($this->table->save($record));
        $recordFromDateInterval = $this->table->get($record->id);
        $this->assertInstanceOf(TimeInterval::class, $recordFromDateInterval->rest);
        $this->assertSame('49:02:03', (string)$recordFromDateInterval->rest);

        // set with seconds and save
        $record->rest = 7200 + 180 + 4;
        $this->assertNotFalse($this->table->save($record));
        $recordFromSeconds = $this->table->get($record->id);
        $this->assertInstanceOf(TimeInterval::class, $recordFromSeconds->rest);
        $this->assertSame('02:03:04', (string)$recordFromSeconds->rest);
    }

    public function testSaveAsInt(): void
    {
        $record = $this->table->get(1);

        // set with time string and save
        $record->rest_seconds = '00:15:01';
        $this->assertNotFalse($this->table->save($record));
        $recordFromTimeString = $this->table->get($record->id);
        $this->assertInstanceOf(TimeInterval::class, $recordFromTimeString->rest_seconds);
        $this->assertSame('00:15:01', (string)$recordFromTimeString->rest_seconds);

        // set with DateInterval object and save
        $now = FrozenTime::now();
        $record->rest_seconds = $now->diff($now->addDays(2)->addHour()->addMinutes(2)->addSeconds(3));
        $this->assertNotFalse($this->table->save($record));
        $recordFromDateInterval = $this->table->get($record->id);
        $this->assertInstanceOf(TimeInterval::class, $recordFromDateInterval->rest_seconds);
        $this->assertSame('49:02:03', (string)$recordFromDateInterval->rest_seconds);

        // set with seconds and save
        $record->rest_seconds = 7200 + 180 + 4;
        $this->assertNotFalse($this->table->save($record));
        $recordFromSeconds = $this->table->get($record->id);
        $this->assertInstanceOf(TimeInterval::class, $recordFromSeconds->rest_seconds);
        $this->assertSame('02:03:04', (string)$recordFromSeconds->rest_seconds);
    }
}
