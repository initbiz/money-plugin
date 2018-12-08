<?php namespace Initbiz\Money\Behaviors;

use Initbiz\Money\Classes\Helpers;
use Responsiv\Currency\Models\Currency;
use \October\Rain\Extension\ExtensionBase;

/**
 * Behavior to use moneyFields property to define which fields are of type money
 * and which columns in DB store amount and currency_id for the fields
 */

class MoneyFields extends ExtensionBase
{
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

        // somehow October tries to save moneyFields so I have to unset it at the end (it is recreated at init)
        unset($this->model->moneyFields);
    }

    public function makeMoneyMutator($name, $amountColumn, $currencyIdColumn)
    {
        $methodName = 'set'.studly_case($name).'Attribute';
        $model = $this->model;

        $this->model->addDynamicMethod($methodName, function ($value) use ($model, $amountColumn, $currencyIdColumn) {
            $amount = Helpers::removeNonNumeric($value['amount']);
            $model->attributes[$amountColumn] = $amount;
            $currencyId = Currency::findByCode($value['currency'])->id;
            $model->attributes[$currencyIdColumn] = $currencyId;
        });
    }

    public function makeMoneyAccessor($name, $amountColumn, $currencyIdColumn)
    {
        $methodName = 'get'.studly_case($name).'Attribute';
        $model = $this->model;

        $model->addDynamicMethod($methodName, function ($value) use ($model, $amountColumn, $currencyIdColumn) {
            $value = [];

            if ($model->exists) {
                if (isset($model->$amountColumn)) {
                    $value['amount'] = $model->amount;
                }
                if (isset($model->$currencyIdColumn)) {
                    $value['currency'] = Currency::find($model->$currencyIdColumn)->currency_code;
                }
            }

            return $value;
        });
    }
}
