<?php namespace Initbiz\Money;

use Backend;
use System\Classes\PluginBase;
use Initbiz\Money\Classes\Helpers;

/**
 * Money Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * @var array Plugin dependencies
     */
    public $require = ['Responsiv.Currency'];

    /**
     * Registers any form widgets implemented in this plugin.
     */
    public function registerFormWidgets()
    {
        return [
            'Initbiz\Money\FormWidgets\Money' => [
                'label' => 'Money',
                'code'  => 'money'
            ]
        ];
    }

    public function registerListColumnTypes()
    {
        return [
            'money' => [$this, 'evalMoneyColumn'],
        ];
    }

    public function evalMoneyColumn($value, $column, $record)
    {
        if (empty($value)) {
            return null;
        }
        return Helpers::formatMoney($value['amount'], $value['currency']);
    }
}
