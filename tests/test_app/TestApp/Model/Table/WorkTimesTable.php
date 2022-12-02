<?php
/*
 * Copyright 2022 ELASTIC Consultants Inc.
 */
declare(strict_types=1);

namespace TestApp\Model\Table;

use Cake\Database\Schema\TableSchemaInterface;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use TestApp\Model\Entity\WorkTime;

/**
 * @method WorkTime get($primaryKey, $options = [])
 * @method WorkTime save(EntityInterface $entity, $options = [])
 * @method WorkTime newEntity($data = null, array $options = [])
 * @method WorkTime patchEntity(EntityInterface $entity, array $data, array $options = [])
 */
class WorkTimesTable extends Table
{
    /**
     * @inheritDoc
     */
    public function initialize(array $config): void
    {
        $this->setEntityClass(WorkTime::class);
    }

    /**
     * @inheritDoc
     */
    protected function _initializeSchema(TableSchemaInterface $schema): TableSchemaInterface
    {
        parent::_initializeSchema($schema);

        $schema->setColumnType('rest', 'time_interval');
        $schema->setColumnType('rest_seconds', 'time_interval_int');
        $schema->setColumnType('duration', 'time_interval');

        return $schema;
    }

    /**
     * @inheritDoc
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator->add('rest', 'timeInterval', [
            'rule' => 'timeInterval',
            'provider' => 'timeInterval',
        ]);
        $validator->add('rest_seconds', 'timeInterval', [
            'rule' => 'timeInterval',
            'provider' => 'timeInterval',
        ]);
        $validator->add('duration', 'timeInterval', [
            'rule' => 'timeInterval',
            'provider' => 'timeInterval',
        ]);

        return $validator;
    }
}
