<?php


function checkWeeklyData($weeklyData)
{
    $money = 0;
    $count = 0;
    foreach ($weeklyData as $data) {
        if ($data->currency === 'USD') {
            $money += $data->amount;
        } elseif ($data->currency === 'EUR') {
            $money += $data->amount / config('currency.EUR');
        } else {
            $money += $data->amount / config('currency.JPY');
        }
        $count += 1;
    }
    $data = [
        'money' => $money,
        'count' => $count
    ];
    return $data;
}


function convertCurrency($amount, $currency)
{
    $money = 0;
    if ($currency === 'USD') {
        $money = $amount;
    } elseif ($currency === 'EUR') {
        $money = $amount / config('currency.EUR');
    } elseif ($currency === 'JPY') {
        $money = $amount / config('currency.JPY');
    }
    return number_format($money, 2, '.', '');
}
