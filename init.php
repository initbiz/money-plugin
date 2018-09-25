<?php namespace Initbiz\Money;

use Yaml;
use File;
use Event;
use Backend;
use BackendMenu;
use Responsiv\Currency\Models\Currency;
use Responsiv\Currency\Controllers\Currencies;

Currencies::extendFormFields(function ($widget) {
    if (!$widget->model instanceof Currency) {
        return;
    }

    $configFile = __DIR__ . '/config/extend_currencies_form.yaml';
    $config = Yaml::parse(File::get($configFile));
    $widget->addTabFields($config);
});
