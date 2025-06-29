<?php

declare(strict_types=1);

namespace Initbiz\Money\Behaviors;

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

    /**
     * Extend the model with accessors and mutators for moneyFields defined in it
     *
     * @return void
     */
    public function makeMoneyFields()
    {
        foreach ($this->model->moneyFields as $name => $columns) {
            $this->makeMoneyMutator($name, $columns['amountColumn'], $columns['currencyIdColumn']);
            $this->makeMoneyAccessors($name, $columns['amountColumn'], $columns['currencyIdColumn']);
        }

        // After mutators and accessors are created we have to unset moneyFields
        // so that Laravel will not try to save it to DB as it is a public attribute
        unset($this->model->moneyFields);
    }

    /**
     * Extend the model with set*MoneyField*Attribute
     *
     * @param string $name name of the attribute to add money mutator
     * @param string $amountColumn unsigned integer or money type column name of the model
     * @param string $currencyIdColumn unsigned integer foreign key with the responsiv_currency.id
     * @return void
     */
    public function makeMoneyMutator($name, $amountColumn, $currencyIdColumn)
    {
        $methodName = 'set' . studly_case($name) . 'Attribute';
        $model = $this->model;

        $model->addDynamicMethod($methodName, function ($value) use ($model, $amountColumn, $currencyIdColumn) {
            $model->setAttribute($amountColumn, empty($value['amount'])
                ? 0
                : Helpers::removeNonNumeric((string) $value['amount']));

            $model->setAttribute($currencyIdColumn, empty($value['currency'])
                ? Currency::getPrimary()->id
                : Currency::findByCode($value['currency'])->id);
        });
    }

    /**
     * Extend the model with get*MoneyField*Attribute
     *
     * @param string $name name of the attribute to add money accessor
     * @param string $amountColumn unsigned integer or money type column name of the model
     * @param string $currencyIdColumn unsigned integer foreign key with the responsiv_currency.id
     * @return void
     */
    public function makeMoneyAccessors($name, $amountColumn, $currencyIdColumn)
    {
        $model = $this->model;

        /**
         * $model->price will return array with amount and currency like
         * [
         *    'amount' => 1234,
         *    'currency' => 'USD'
         * ]
         *
         * Which represents the amount of $12.34
         * Use price_formatted or price_decimal to get it in nicer format (see below)
         *
         * @return array
         */
        $methodName = 'get' . studly_case($name) . 'Attribute';
        $model->addDynamicMethod($methodName, function ($value) use ($model, $amountColumn, $currencyIdColumn) {
            $value = [];

            if (isset($model->attributes[$amountColumn]) && !empty($model->attributes[$amountColumn])) {
                $value['amount'] = (int) $model->getAttribute($amountColumn);
            } else {
                $value['amount'] = 0;
            }

            if (isset($model->$currencyIdColumn)) {
                $currencyId = $model->getAttribute($currencyIdColumn);
                $value['currency'] = Currency::find($currencyId)->currency_code;
            } else {
                $value['currency'] = Currency::getPrimary()->currency_code;
            }

            return $value;
        });

        /**
         * $model->price_decimal will return string like "12.34"
         *
         * @return string
         */
        $methodName = 'get' . studly_case($name) . 'DecimalAttribute';
        $model->addDynamicMethod($methodName, function () use ($model, $name) {
            return Helpers::formatAmountDotBoth($model->$name);
        });

        /**
         * $model->price_formatted will return string like "$12.34"
         *
         * @return string
         */
        $methodName = 'get' . studly_case($name) . 'FormattedAttribute';
        $model->addDynamicMethod($methodName, function () use ($model, $name) {
            return Helpers::formatMoneyBoth($model->$name);
        });
    }
}
