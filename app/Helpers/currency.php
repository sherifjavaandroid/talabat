<?php

function currencyFormat($value, $currency = null)
{

    //format currency
    $value = number_format(
        (float) $value,
        setting('ui.currency.decimals', 2),
        setting('ui.currency.decimal_format', "."),
        setting('ui.currency.format', ",")
    );

    //
    if (empty($currency)) {
        $currency = setting('currency', '$');
    }

    //side
    $currencySide = setting('ui.currency.location', 'left');
    if (strtolower($currencySide) != "left") {
        return $value . " " . $currency;
    } else {
        return $currency . " " . $value;
    }
}

function currencyValueFormat($value)
{

    //format currency
    $value = number_format(
        (float) $value,
        setting('ui.currency.decimals', 2),
        setting('ui.currency.decimal_format', "."),
        setting('ui.currency.format', ",")
    );
    return (float)$value;
}
