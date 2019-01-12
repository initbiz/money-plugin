# Introduction
Everything you need to have installed to work with money in your OctoberCMS app.

Money is always expressed with two factors. It is value (amount) and a currency. Sometimes the latter is default but it always is defined.

Furthermore amounts should always be stored in databases using integer or other monetary types and never using floats or doubles. You can find great article explaining this [here](https://spin.atomicobject.com/2014/08/14/currency-rounding-errors/).

So this plugin was created to help with handling such complication.

The plugin uses:
* [Responsiv.Currency](https://octobercms.com/plugin/responsiv-currency) as a base for managing currencies,
* [Brick/Money](https://github.com/brick/money) library to manage money in backend,
* [Dinero.js](https://sarahdayan.github.io/dinero.js/) to manage money in frontend.

# Documentation

## Backend form widget

The plugins adds backend form widget called money. You can use it in your `config_form.yaml` files:
```yaml
    price:
        type: money
        mode: amountcurrency
```

It is designed to input money made of two factors: amount and currency. By default you will get two fields:

* Amount input which gets parsed every time you refresh page and input a value (using currency)
* Select with currencies defined in [Responsiv.Currency](https://octobercms.com/plugin/responsiv-currency).

You have to have defined two columns for every money type field. After that you have to write your own mutator and accessor or use `MoneyFields` behavior described below.

### Modes
Right now there are two modes of the widget:
* `amountcurrency` - the default one
* `amount`

The latter will use currency set as default in `Responsiv.Currency` settings in backend while the first one will render you dropdown with other currencies.

I currently work on third mode: `currency`. This will be useful for changing all currencies on page with one dropdown (for example when you have one currency on whole invoice where you have a lot of amount inputs).

## MoneyFields behavior
The behavior uses `$moneyFields` attribute to add dynamically mutators and accessors for money fields.

Using the bevarior looks like in this example:

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

This code will dynamically add two methods to `Product` model: `setPriceAttribute` and `getPriceAttribute`. Both of them will be using `amount` as an amount column name (and model attribute) and `currency_id` as a currency column name (and model attribute as well).

If you want to have only one currency in your model there should not be problems with using the same column name for more than one field. Remember though that if you will not change currency for all inputs that have the same column, while saving the value might be overrided.

## Future plans (TODO)
* Currency mode (to change all currencies on page)
* Automatically converting and displaying amounts in selected format (extending `Responsiv.Currency`)
* Register settings to define if we want to use localized config or 'per currency' format
