<?php namespace Initbiz\Money;

use Yaml;
use File;
use Event;
use Backend;
use BackendMenu;
use System\Classes\PluginManager;

$pluginManager = PluginManager::instance();
$plugins = $pluginManager->getPlugins();
if (array_key_exists('Responsiv.Currency', $plugins)) {
    \Responsiv\Currency\Controllers\Currencies::extendFormFields(function ($widget) {
        if (!$widget->model instanceof \Responsiv\Currency\Models\Currency) {
            return;
        }

        $configFile = __DIR__ . '/config/extend_currencies_form.yaml';
        $config = Yaml::parse(File::get($configFile));
        $widget->addFields($config);
    });

    \Responsiv\Currency\Models\Currency::extend(function ($model) {
        //By default 2 fraction digits
        $model->addDynamicMethod('getInitbizMoneyFractionDigitsAttribute', function ($value) use ($model) {
            if ($value === null) {
                return 2;
            }
            return $value;
        });
    });
}
