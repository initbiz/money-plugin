<?php namespace Initbiz\Money\FrontendFormWidgets;

use Html;
use Backend\Classes\FormField;
use Initbiz\Money\Classes\Helpers;
use RainLab\Location\Models\Setting;
use Responsiv\Currency\Models\Currency as CurrencyModel;
use Initbiz\PowerComponents\Classes\FrontendFormWidgetBase;

/**
 * Money input
 */
class Money extends FrontendFormWidgetBase
{
    /**
     * @var string Money format to display
     */
    public $format = null;

    /**
     * @var string Mode of the field (amount|amountcurrency)
     */
    public $mode = 'amountcurrency';

    /**
     * {@inheritDoc}
     */
    public $defaultAlias = 'money';

    /**
     * {@inheritDoc}
     */
    public function init()
    {
        $this->fillFromConfig([
            'format',
            'mode',
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function render()
    {
        $this->prepareVars();

        return $this->makePartial('money');
    }

    /**
     * Prepares the list data
     */
    public function prepareVars()
    {
        $primaryCurrency = CurrencyModel::getPrimary();
        $currency = $primaryCurrency;

        $value = $this->getLoadValue();

        if ($value) {
            $amount = $value['amount'];
            $currencyCode = $value['currency'];
            $currency = CurrencyModel::findByCode($currencyCode);
        } else {
            $amount = 0;
            $currencyCode = $primaryCurrency->currency_code;
        }

        $name = $this->formField->getName()."[amount]";
        $currenciesFieldName = $this->formField->getName()."[currency]";

        $currenciesField = new FormField($currenciesFieldName, $this->label."_currency");
        $currenciesField->options = CurrencyModel::listEnabled();
        $currenciesField->value = $currencyCode;
        $currenciesField->attributes = $this->formField->attributes;
        $currenciesField->disabled = $this->formField->disabled;
        $currenciesField->readOnly = $this->formField->readOnly;

        $currencyConfig = $currency;

        $this->vars['name'] = $name;
        $this->vars['format'] = $this->format;
        $this->vars['amount'] = $amount;
        $this->vars['primaryCurrency'] = $primaryCurrency;
        $this->vars['currenciesField'] = $currenciesField;
        $this->vars['currencyConfig'] = $currencyConfig;
    }

    /**
     * {@inheritDoc}
     */
    public function getSaveValue($value)
    {
        if ($this->formField->disabled || $this->formField->hidden) {
            return FormField::NO_SAVE_DATA;
        }

        $value['amount'] = (int) Helpers::removeNonNumeric($value['amount']);
        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function loadAssets()
    {
        $this->addCss(['~/plugins/initbiz/money/formwidgets/money/assets/css/money.css']);
        $this->addJs([
            '~/plugins/initbiz/money/assets/js/libs/dinero.js/src/dinero.min.js',
            '~/plugins/initbiz/money/assets/js/money-helpers.js',
            '~/plugins/initbiz/money/assets/js/money-manipulator.js',
            '~/plugins/initbiz/money/formwidgets/money/assets/js/config-manager.js',
            '~/plugins/initbiz/money/formwidgets/money/assets/js/money-widget.js',
            '~/plugins/initbiz/money/formwidgets/money/assets/js/money-widget-handlers.js'
        ]);
    }
}
