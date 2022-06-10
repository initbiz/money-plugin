<?php

namespace Initbiz\Money\EventHandlers;

use Responsiv\Currency\Models\Currency;
use Responsiv\Currency\Controllers\Currencies;

class ResponsivCurrencyHandler
{
    public function subscribe($event)
    {
        $this->extendCurrencyModel($event);
        $this->extendCurrencyController($event);
    }

    public function extendCurrencyModel($event)
    {
        Currency::extend(function ($model) {
            // By default 2 fraction digits
            $model->addDynamicMethod('getInitbizMoneyFractionDigitsAttribute', function ($value) {
                if ($value === null) {
                    return 2;
                }
                return $value;
            });
        });
    }

    public function extendCurrencyController($event)
    {
        Currencies::extendFormFields(function ($widget) {
            if (!$widget->model instanceof Currency) {
                return;
            }

            $config = [
                'initbiz_money_fraction_digits' => [
                    'label' => "initbiz.money::lang.currency_form.fraction_digits",
                    'type' => 'number',
                    'span' => 'right',
                    'default' => 2,
                ]
            ];

            $widget->addFields($config);
        });
    }
}
