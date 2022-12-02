<?php
/*
 * Copyright 2022 ELASTIC Consultants Inc.
 */
declare(strict_types=1);

namespace TestApp\Model\Entity;

use Cake\Database\Type;
use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;
use Elastic\TimeInterval\ValueObject\TimeInterval;

/**
 * @property int $id
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
