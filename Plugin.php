<?php namespace Initbiz\Money;

use Backend;
use System\Classes\PluginBase;

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
}
