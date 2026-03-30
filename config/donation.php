<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Minimum Donation Amount
    |--------------------------------------------------------------------------
    |
    | The minimum amount that can be donated in the default currency.
    | This value is used for validation and display purposes.
    |
    */
    'min_amount' => env('DONATION_MIN_AMOUNT', 5),
];
