<?php

declare(strict_types=1);

namespace Initbiz\Money;

use Event;
use System\Classes\PluginBase;
use Initbiz\Money\Classes\Helpers;
use Initbiz\Money\FormWidgets\Money;

/**
 * Money Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * @var array Plugin dependencies
     */
    public $require = [
        'Responsiv.Currency'
    ];

    public function boot()
    {
        Event::subscribe(\Initbiz\Money\EventHandlers\ResponsivCurrencyHandler::class);
        Event::subscribe(\Initbiz\Money\EventHandlers\MoneyHandler::class);
    }

    /**
     * Registers any form widgets implemented in this plugin.
     */
    public function registerFormWidgets()
    {
        return [
            Money::class => [
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

        if (is_string($value)) {
            $value = json_decode($value, true);
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
