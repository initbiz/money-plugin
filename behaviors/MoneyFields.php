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
            $this->makeMoneySetter($name, $columns['amountColumn'], $columns['currencyIdColumn']);
            $this->makeMoneyGetter($name, $columns['amountColumn'], $columns['currencyIdColumn']);
        }
    }

    public function makeMoneySetter($name, $amountColumn, $currencyIdColumn)
    {
        $methodName = 'set'.studly_case($name).'Attribute';
        $model = $this->model;

        $model->addDynamicMethod($methodName, function ($value) use ($model, $amountColumn, $currencyIdColumn) {
            $amount = Helpers::removeNonNumeric($value['amount']);
            $model->attributes[$amountColumn] = $amount;
            // if (!ctype_digit($amount)) {
            //     throw new \ValidationException(
            //         [
            //             'amount' =>
            //                 \Lang::get('system::validation.integer', [
            //                     'attribute' => \Lang::get('initbiz.cumulussubscriptions::lang.plan_subscription.amount')
            //                 ])
            //         ]
            //     );
            // }
            $currencyId = Currency::findByCode($value['currency'])->id;
            $model->attributes[$currencyIdColumn] = $currencyId;
        });
    }

    public function makeMoneyGetter($name, $amountColumn, $currencyIdColumn)
    {
        $methodName = 'get'.studly_case($name).'Attribute';
        $model = $this->model;

        $model->addDynamicMethod($methodName, function ($value) use ($model, $amountColumn, $currencyIdColumn) {
            $value['amount'] = $model->amount;
            $value['currency'] = Currency::find($model->$currencyIdColumn)->currency_code;
            return $value;
        });
    }
}
