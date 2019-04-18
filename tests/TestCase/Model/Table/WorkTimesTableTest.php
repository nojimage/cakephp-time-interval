<?php

namespace Elastic\TimeInterval\Test\TestCase\Model\Table;

use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Elastic\TimeInterval\Model\Table\WorkTimesTable;
use Elastic\TimeInterval\ValueObject\TimeInterval;

class WorkTimesTableTest extends TestCase
{
    public $fixtures = [
        'plugin.Elastic/TimeInterval.WorkTimes',
    ];

    /**
     * @var WorkTimesTable
     */
    private $table;

    public function setUp()
    {
        parent::setUp();
        $this->table = TableRegistry::get('Elastic/TimeInterval.WorkTimes');
    }

    public function tearDown()
    {
        unset($this->table);
        parent::tearDown();
    }

    public function testGetFromDb()
    {
        $record = $this->table->get(1);
        $this->assertInstanceOf(TimeInterval::class, $record->rest);
        $this->assertSame('01:00:00', (string)$record->rest);
        $this->assertInstanceOf(TimeInterval::class, $record->duration);
        $this->assertSame('08:00:00', (string)$record->duration);
    }

    public function testMutation()
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

    public function testEntityMutation()
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

    public function testSaveAs()
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
}
