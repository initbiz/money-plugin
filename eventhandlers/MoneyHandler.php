<?php

declare(strict_types=1);

namespace Initbiz\Money\EventHandlers;

use Event;

class MoneyHandler
{
    public function subscribe($event)
    {
        $this->addDinero($event);
    }

    /**
     * We need to add dinero before richeditor because for some reason when js is added after richeditor it doesn't work
     *
     * @param $event
     * @return void
     */
    public function addDinero($event)
    {
        Event::listen('backend.form.extendFields', function ($widget) {
            $fields = $widget->getFields();
            foreach ($fields as $field) {
                if (isset($field->config['type']) && $field->config['type'] === 'money') {
                    $widget->getController()->addJs('/plugins/initbiz/money/assets/node_modules/dinero.js/build/umd/dinero.min.js');
                }
            }
        });
    }
}
