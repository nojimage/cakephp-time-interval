# TimeInterval plugin for CakePHP 3.x

<p align="center">
    <a href="LICENSE.txt" target="_blank">
        <img alt="Software License" src="https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square">
    </a>
    <a href="https://github.com/nojimage/cakephp-time-interval/actions" target="_blank">
        <img alt="Build Status" src="https://img.shields.io/github/actions/workflow/status/nojimage/cakephp-time-interval/ci.yml?style=flat-square&branch=cake3">
    </a>
    <a href="https://codecov.io/gh/nojimage/cakephp-time-interval" target="_blank">
        <img alt="Codecov" src="https://img.shields.io/codecov/c/github/nojimage/cakephp-time-interval.svg?style=flat-square">
    </a>
    <a href="https://packagist.org/packages/elstc/cakephp-time-interval" target="_blank">
        <img alt="Latest Stable Version" src="https://img.shields.io/packagist/v/elstc/cakephp-time-interval.svg?style=flat-square">
    </a>
</p>

This plugin provide `time_interval` custom type for MySQL's `TIME`, Postgres's `INTERVAL`,
 and provide `time_interval_int` custom type for seconds as `INTEGER`.
This is a custom type to represent intervals, which CakePHP can treat as a `TimeInterval` object that inherits from `DateInterval`.

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require elstc/cakephp-time-interval
```

### Load plugin

(CakePHP >= 3.6.0) Load the plugin by adding the following statement in your project's `src/Application.php`:

```
$this->addPlugin('Elastic/TimeInterval');
```

(CakePHP <= 3.5.x) Load the plugin by adding the following statement in your project's `config/bootstrap.php` file:

```
Plugin::load('Elastic/TimeInterval', ['bootstrap' => true]);
```

## Usage

### Add column definitions to Table class

```php
use Cake\Database\Schema\TableSchema;

class WorkTimesTable extends Table
{
    protected function _initializeSchema(TableSchema $schema)
    {
        parent::_initializeSchema($schema);

        $schema->setColumnType('duration', 'time_interval');
        // CakePHP <= 3.4.x use columnType() instead.
        $schema->columnType('duration', 'time_interval');

        // If your column type is seconds as INTEGER, Use `time_interval_int` instead.
        $schema->setColumnType('duration_sec', 'time_interval_int');

        return $schema;
    }
}
```

### Add column validation to Table class

Use `timeInterval` rule instead of `time`.
The `timeInterval` rule is in the `timeInterval` validation provider. 

```php
use Cake\Validation\Validator;
use Elastic\TimeInterval\Validation\TimeIntervalValidation;

class WorkTimesTable extends Table
{
    public function validationDefault(Validator $validator)
    {
        // ...

        // CakePHP <= 3.4.x required setProvider. CakePHP >= 3.5 it's not necessary.
        $validator->setProvider('timeInterval', TimeIntervalValidation::class);

        $validator->add('duration', 'timeInterval', [
            'rule' => 'timeInterval',
            'provider' => 'timeInterval',
        ]);

        return $validator;
    }
}
```

### In addition, add mutator to Entity class, it is useful.

```php
use Cake\Database\Type;

class WorkTime extends Entity
{
    protected function _setDuration($value)
    {
        // convert to TimeInterval
        return Type::build('time_interval')->marshal($value);
    }
}

$workTime->duration = '00:15:00';
$workTime->duration = ($startTime)->diff($endTime); // $startTime, $endTime is FrozenTime object.
$workTime->duration = 3600; // as a seconds
```

## NOTE

### MySQL TIME column limitation.

[MySQL :: MySQL 5.7 Reference Manual :: 11.3.2 The TIME Type](https://dev.mysql.com/doc/refman/5.7/en/time.html)

    By default, values that lie outside the TIME range but are otherwise valid are clipped to the closest endpoint of the range. For example,
    '-850:00:00' and '850:00:00' are converted to '-838:59:59' and '838:59:59'. Invalid TIME values are converted to '00:00:00'.
    Note that because '00:00:00' is itself a valid TIME value, there is no way to tell, from a value of '00:00:00' stored in a table,
    whether the original value was specified as '00:00:00' or whether it was invalid.

### DateInterval / TimeInterval construct with date part will be broken time

If you initialize DateInterval with date part, time will not be interpreted correctly.

```php
$workTime->duration = new DateInterval('PT75H4M5S'); // OK
$workTime->duration = new DateInterval('P1M2DT3H4M5S'); // can't get expected time
```
