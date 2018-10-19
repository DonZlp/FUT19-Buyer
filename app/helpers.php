<?php
/**
 * Created by PhpStorm.
 * User: curtiscrewe
 * Date: 18/10/2018
 * Time: 16:02
 */

use Carbon\Carbon;
use App\Models\Transactions;
use \Illuminate\Support\Facades\Config;

/*
 * Global helpers file with misc functions.
 */
if (! function_exists('today_profit')) {

    /**
     * @return float|int
     */
    function today_profit()
    {
        $transactions = Transactions::whereDate('sold_time', Carbon::now()->format('Y-m-d'))->get();
        $profit = 0;
        if(count($transactions) > 0) {
            foreach($transactions as $row) {
                $profit = $profit + (($row->sell_bin *0.95) - $row->buy_bin);
            }
        }
        return $profit;
    }

}

if (! function_exists('total_profit')) {

    /**
     * @return float|int
     */
    function total_profit()
    {
        $transactions = Transactions::all();
        $profit = 0;
        if(count($transactions) > 0) {
            foreach($transactions as $row) {
                $profit = $profit + (($row->sell_bin *0.95) - $row->buy_bin);
            }
        }
        return $profit;
    }

}

if (! function_exists('day_profit')) {

    /**
     * @param $subtract
     * @param $console
     * @return float|int
     */
    function day_profit($subtract, $console)
    {
        $transactions = Transactions::whereDate('sold_time', Carbon::now()->subDays($subtract)->toDateString())->where('platform', $console)->get();
        $profit = 0;
        if(count($transactions) > 0) {
            foreach($transactions as $row) {
                $profit = $profit + (($row->sell_bin *0.95) - $row->buy_bin);
            }
        }
        return $profit;
    }

}

if (! function_exists('autobuyer_status')) {

    /**
     * @param bool $friendly
     * @return int|string
     */
    function autobuyer_status($friendly = false)
    {
        switch(Config::get('settings.autobuyer_status')) {
            case "0":
                return ($friendly == false ? 0 : '<span class="label label-danger">Not running</span>');
                break;
            case "1":
                return ($friendly == false ? 1 : '<span class="label label-success">Running</span>');
                break;
        }
    }

}

if (! function_exists('calculate_prices')) {

    /**
     * @param $lowest_bin
     * @param $buy_percentage
     * @param $sell_percentage
     * @return array
     */
    function calculate_prices($lowest_bin, $buy_percentage, $sell_percentage)
    {
        if($lowest_bin < 10000) {
            $sell_bin = $sell_percentage / 100 * $lowest_bin;
            $sell_bin = ceil($sell_bin / 100) * 100;
            $sell_bid = $sell_bin - 100;
            $new_bin = $buy_percentage / 100 * $lowest_bin;
            $buy_bin = floor($new_bin / 100) * 100;
        } elseif ($lowest_bin > 10000 & $lowest_bin < 50000) {
            $sell_bin = $sell_percentage / 100 * $lowest_bin;
            $sell_bin = 250 * round($sell_bin / 250);
            $sell_bid = $sell_bin - 250;
            $new_bin = $buy_percentage / 100 * $lowest_bin;
            $buy_bin = floor($new_bin / 250) * 250;
        } elseif ($lowest_bin > 50000 & $lowest_bin < 100000) {
            $sell_bin = $sell_percentage / 100 * $lowest_bin;
            $sell_bin = 500 * round($sell_bin / 500);
            $sell_bid = $sell_bin - 500;
            $new_bin = $buy_percentage / 100 * $lowest_bin;
            $buy_bin = floor($new_bin / 500) * 500;
        } else {
            $sell_bin = $sell_percentage / 100 * $lowest_bin;
            $sell_bin = 1000 * round($sell_bin / 1000);
            $sell_bid = $sell_bin - 1000;
            $new_bin = $buy_percentage / 100 * $lowest_bin;
            $buy_bin = floor($new_bin / 1000) * 1000;
        }
        return [
            "max_bin" => $buy_bin,
            "sell_bid" => $sell_bid,
            "sell_bin" => $sell_bin
        ];
    }

}