<?php namespace Initbiz\Money\Behaviors;

use System\Classes\ModelBehavior;
use Initbiz\Money\Classes\Helpers;
use Responsiv\Currency\Models\Currency;

/**
 * Behavior to use moneyFields property to define which fields are of type money
 * and which columns in DB store amount and currency_id for the fields
 */

class MoneyFields extends ModelBehavior
{
    protected $requiredProperties = ['moneyFields'];

    protected $model;

    public function __construct($model)
    {
        $this->model = $model;

        $this->makeMoneyFields();
    }

    public function makeMoneyFields()
    {
        foreach ($this->model->moneyFields as $name => $columns) {
            $this->makeMoneyMutator($name, $columns['amountColumn'], $columns['currencyIdColumn']);
            $this->makeMoneyAccessor($name, $columns['amountColumn'], $columns['currencyIdColumn']);
        }

        // After mutators and accessors are created we have to unset moneyFields so that Laravel will not try to save it to DB
        unset($this->model->moneyFields);
    }

    public function makeMoneyMutator($name, $amountColumn, $currencyIdColumn)
    {
        $methodName = 'set'.studly_case($name).'Attribute';
        $model = $this->model;

        $model->addDynamicMethod($methodName, function ($value) use ($model, $amountColumn, $currencyIdColumn) {
            if (!empty($value['amount']) && !empty($value['currency'])) {
                $amount = Helpers::removeNonNumeric($value['amount']);
                $model->attributes[$amountColumn] = $amount;
                $currencyId = Currency::findByCode($value['currency'])->id;
                $model->attributes[$currencyIdColumn] = $currencyId;
            }
        });
    }

    public function makeMoneyAccessor($name, $amountColumn, $currencyIdColumn)
    {
        $methodName = 'get'.studly_case($name).'Attribute';
        $model = $this->model;

        $model->addDynamicMethod($methodName, function ($value) use ($model, $amountColumn, $currencyIdColumn) {
            $value = [];

            if (isset($model->$amountColumn)) {
                $value['amount'] = (int) $model->$amountColumn;
            } else {
                $value['amount'] = 0;
            }

            if (isset($model->$currencyIdColumn)) {
                $value['currency'] = Currency::find($model->$currencyIdColumn)->currency_code;
            } else {
                $value['currency'] = Currency::getPrimary()->currency_code;
            }

            return $value;
        });
    }
}
