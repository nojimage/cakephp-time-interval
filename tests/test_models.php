<?php
/**
 * @codingStandardsIgnoreFile
 */
namespace Elastic\TimeInterval\Model\Entity {

    use Cake\Database\Type;
    use Cake\I18n\FrozenTime;
    use Cake\ORM\Entity;
    use Elastic\TimeInterval\ValueObject\TimeInterval;

    /**
     * @property integer $id
     * @property FrozenTime $start
     * @property FrozenTime $end
     * @property TimeInterval $rest
     * @property TimeInterval $rest_seconds
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

    use Cake\Database\Schema\TableSchema;
    use Cake\Datasource\EntityInterface;
    use Cake\ORM\Table;
    use Cake\Validation\Validator;
    use Elastic\TimeInterval\Model\Entity\WorkTime;
    use Elastic\TimeInterval\Validation\TimeIntervalValidation;

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
            $schema->columnType('rest_seconds', 'time_interval_int');
            $schema->columnType('duration', 'time_interval');
            error_reporting(E_ALL);

            return $schema;
        }

        public function validationDefault(Validator $validator)
        {
            // for CakePHP <= 3.4
            if (!$validator->getProvider('timeInterval')) {
                $validator->setProvider('timeInterval', TimeIntervalValidation::class);
            }

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
}
