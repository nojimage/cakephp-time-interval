<?php

namespace Elastic\TimeInterval\Model\Entity {

    use Cake\Database\Type;// @codingStandardsIgnoreLine
    use Cake\I18n\FrozenTime;// @codingStandardsIgnoreLine
    use Cake\ORM\Entity;// @codingStandardsIgnoreLine
    use Elastic\TimeInterval\ValueObject\TimeInterval;// @codingStandardsIgnoreLine

    /**
     * @property integer $id
     * @property FrozenTime $start
     * @property FrozenTime $end
     * @property TimeInterval $rest
     * @property TimeInterval $duration
     */
    class WorkTime extends Entity
    {
        protected $_accessible = ['*' => true, 'id' => false];

        protected function _setDuration($value)
        {
            return Type::build('time_interval')->marshal($value);
        }
    }
}

namespace Elastic\TimeInterval\Model\Table {

    use Cake\Database\Schema\TableSchema;// @codingStandardsIgnoreLine
    use Cake\Datasource\EntityInterface;// @codingStandardsIgnoreLine
    use Cake\ORM\Table;// @codingStandardsIgnoreLine
    use Cake\Validation\Validator;// @codingStandardsIgnoreLine
    use Elastic\TimeInterval\Model\Entity\WorkTime;// @codingStandardsIgnoreLine

    /**
     * @method WorkTime get($primaryKey, $options = [])
     * @method WorkTime save(EntityInterface $entity, $options = [])
     * @method WorkTime newEntity($data = null, array $options = [])
     * @method WorkTime patchEntity(EntityInterface $entity, array $data, array $options = [])
     */
    class WorkTimesTable extends Table
    {
        public function initialize(array $config)
        {
            $this->setEntityClass(WorkTime::class);
        }

        protected function _initializeSchema(TableSchema $schema)
        {
            parent::_initializeSchema($schema);

            error_reporting(E_ALL & ~E_USER_DEPRECATED);
            $schema->columnType('rest', 'time_interval');
            $schema->columnType('duration', 'time_interval');
            error_reporting(E_ALL);

            return $schema;
        }

        public function validationDefault(Validator $validator)
        {
            $validator->add('rest', 'timeInterval', [
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
}
