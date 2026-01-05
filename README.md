# Money - Plugin to use money in OctoberCMS painlessly.

![Money banner](https://raw.githubusercontent.com/initbiz/money-plugin/master/docs/money.png)

This is a repo of OctoberCMS plugin by [InIT.biz](https://init.biz).

## Introduction

Currency and amount management made simple. A fast little plugin that quickly addresses the problem of floating point numbers and currency rounding errors in your code.

Here’s a great article that explains the potential problem in more detail - https://spin.atomicobject.com/2014/08/14/currency-rounding-errors/

The plugin requires:

-   [Responsiv.Currency](https://octobercms.com/plugin/responsiv-currency) as a base for managing currencies,
-   [Brick/Money](https://github.com/brick/money) library to manage money in backend,
-   [Dinero.js](https://sarahdayan.github.io/dinero.js/) to manage money in frontend.

## Installation note (since January 2019)

Since January 2019 [Brick/Money](https://github.com/brick/money) package requires PHP7.1 as a minimum version while OctoberCMS overrides platform to PHP7.0 ([described here](https://github.com/octobercms/october/issues/4181)). As a consequence, if you want to install the plugin from Marketplace right now, ensure you have changed your root `composer.json` PHP version to 7.1.

## Backend form widget

The plugin adds a backend form widget called money. You can use it in your `config_form.yaml` files:

```yaml
price:
    type: money
    mode: amountcurrency
```

It is designed to input money made of two factors: amount and currency. By default you will get two fields:

-   Amount input which gets parsed every time you refresh the page and input a value (using currency)
-   Select with currencies defined in [Responsiv.Currency](https://octobercms.com/plugin/responsiv-currency).

You have to have defined two columns for every money type field. After that, you have to write your own mutator and accessor or use `MoneyFields` behavior described below.

### Modes

Right now there are two modes of the widget:

-   `amountcurrency` - the default one
-   `amount`

The latter will use currency set as default in `Responsiv.Currency` settings in the backend while the first one will render you a dropdown with other currencies.

I currently work on the third mode: `currency`. This will be useful for changing all currencies on page with one dropdown (for example when you have one currency on the whole invoice where you have a lot of amount inputs).

## MoneyFields behavior

The behavior uses `$moneyFields` attribute to add dynamically mutators and accessors for money fields.

Using the behavior looks like in this example:

```php
    class Product extends Model
    {
        public $moneyFields = [
            'price' => [
                'amountColumn' => 'amount',
                'currencyIdColumn' => 'currency_id'
            ]
        ];
        //...
        public $implement = [
            'Initbiz.Money.Behaviors.MoneyFields'
        ];
        //...
    }
```

This code will dynamically add two methods to the `Product` model: `setPriceAttribute` and `getPriceAttribute`. Both of them will be using `amount` as an amount column name (and model attribute) and `currency_id` as a currency column name (and model attribute as well).

If you want to have only one currency in your model there should not be problems with using the same column name for more than one field. Remember though that if you will not change the currency for all inputs that have the same column while saving the value might be overridden.

## Future plans

-   Currency mode (to change all currencies on the page)
-   Automatically converting and displaying amounts in the selected format (extending `Responsiv.Currency`)
-   Register settings to define if we want to use localized config or 'per currency' format
