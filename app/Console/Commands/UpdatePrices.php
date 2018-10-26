<?php
/**
 * Created by PhpStorm.
 * User: curtiscrewe
 * Date: 19/10/2018
 * Time: 01:30
 */

namespace App\Console\Commands;

use Backpack\Settings\app\Models\Setting;
use App\Models\Players;
use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Helpers\FUTBIN;

class UpdatePrices extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update_prices:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update player prices';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        if(Setting::get('autobuyer_status') == '0') {
            $this->info("Autobuyer disabled.");
            return;
        }
        $interval = Setting::get('price_update_interval');
        $run_query = Players::where('status', '1')->where('auto_pricing', '1')->where(function ($query) use($interval) {
            $query->where('last_price_update', '<', Carbon::now()->subMinutes($interval)->toDateTimeString())
                ->orWhereNull('last_price_update');
        })->get();
        $buy_percentage = Setting::get('buy_percentage');
        $sell_percentage = Setting::get('sell_percentage');
        foreach ($run_query as $row) {
            $platforms = [
                "PS", "XBOX", "PC"
            ];
            $info = [];
            foreach($platforms as $platform) {
                $futbin = new FUTBIN($platform);
                $futbin->selectPlayer($row->futbin_id, $row->resource_id);
                $lowest_bin = $futbin->getLowestBIN();
                $ranges = $futbin->getPriceRanges();
                $prices = calculate_prices($lowest_bin['lowest_bin'], $buy_percentage, $sell_percentage);
                $info[$platform] = array(
                    "max_bin" => $prices['max_bin'],
                    "sell_bid" => $prices['sell_bid'],
                    "sell_bin" => $prices['sell_bin'],
                    "lowest_bin" => (float)$lowest_bin['lowest_bin'],
                    "start_range" => $ranges['start_range'],
                    "end_range" => $ranges['end_range']
                );
            }

            Players::find($row->id)->update([
                "pc_lowest_bin" => $info['PC']['lowest_bin'],
                "pc_buy_bin" => $info['PC']['max_bin'],
                "pc_sell_bid" => $info['PC']['sell_bid'],
                "pc_sell_bin" => $info['PC']['sell_bin'],
                "xb_lowest_bin" => $info['XBOX']['lowest_bin'],
                "xb_buy_bin" => $info['XBOX']['max_bin'],
                "xb_sell_bid" => $info['XBOX']['sell_bid'],
                "xb_sell_bin" => $info['XBOX']['sell_bin'],
                "ps_lowest_bin" => $info['PS']['lowest_bin'],
                "ps_buy_bin" => $info['PS']['max_bin'],
                "ps_sell_bid" => $info['PS']['sell_bid'],
                "ps_sell_bin" => $info['PS']['sell_bin'],
                "last_price_update" => new Carbon
            ]);
        }
    }

}