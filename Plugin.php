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

    /**
     * Registers any frontend form widgets implemented in this plugin for PowerComponents plugin
     */
    public function registerFrontendFormWidgets()
    {
        return [
            'Initbiz\Money\FrontendFormWidgets\Money' => 'money',
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

    public function registerMarkupTags()
    {
        return [
            'filters' => [
                'money' => [$this, 'formatMoney']
            ],
        ];
    }

    public function formatMoney($value)
    {
        return Helpers::formatMoneyBoth($value);
    }
}
