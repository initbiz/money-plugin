/**
 * Money form widget as an October disposable control (data-control="money"): connected whenever the field
 * appears - with the page, an AJAX update, a popup, a lazy repeater item or a Turbo visit.
 * The server renders the amount in minor units, the control shows it formatted and formats what the user types.
 */
oc.registerControl('money', class extends oc.ControlBase {
    connect() {
        this.configManager = new $.ConfigManager($('.money-config', this.element));
        this.manipulator = new $.MoneyManipulator(this.configManager.get());
        this.$input = $('.amount-input', this.element);

        this.$input.val(this.manipulator.parseCurrencyRaw(this.$input.val())).trigger('change');

        this.$input.on('blur', this.proxy(this.onBlur));
        this.$input.on('focus', this.proxy(this.onFocus));
    }

    disconnect() {
        this.$input.off('blur', this.proxy(this.onBlur));
        this.$input.off('focus', this.proxy(this.onFocus));
    }

    onBlur() {
        this.$input.val(this.manipulator.parseCurrency(this.$input.val())).trigger('change');
    }

    // Select the whole amount on the first click
    onFocus() {
        setTimeout(() => this.$input.select(), 50);
    }
});
