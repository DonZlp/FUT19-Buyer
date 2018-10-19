<?php
/**
 * Created by PhpStorm.
 * User: curtiscrewe
 * Date: 18/10/2018
 * Time: 14:12
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Accounts;
use App\Models\Players;
use App\Models\Transactions;

class Dashboard extends Controller
{

    public function main()
    {
        $ps_players = Players::query()->where('ps_buy_bin', '!=', '0')->where('status', '1');
        $xbox_players = Players::query()->where('xb_buy_bin', '!=', '0')->where('status', '1');
        $accounts = Accounts::query()->where('status', '1');
        $sales = Transactions::query()->whereNotNull('sold_time');
        $buys = Transactions::query();
        $coins = Accounts::query();
        return view('dashboard', [
            'ps_players' => $ps_players,
            'xbox_players' => $xbox_players,
            'accounts' => $accounts,
            'sales' => $sales,
            'buys' => $buys,
            'coins' => $coins
        ]);

    }

    public function graphData(Request $request)
    {
        abort_unless($request->ajax(), 403);
        $result = [];
        for($i = 0; $i <= 6; $i++){
            $day = abs($i - 6);
            $result['XBOX'][$i] = day_profit($day, "XBOX");
            $result['PS4'][$i] = day_profit($day, "PS4");
        }
        return $result;
    }

}