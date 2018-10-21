<?php
/**
 * Created by PhpStorm.
 * User: curtiscrewe
 * Date: 19/10/2018
 * Time: 03:49
 */

namespace App\Console\Commands;

use App\Models\Accounts;
use App\Models\Players;
use App\Models\Transactions;
use Carbon\Carbon;
use FUTApi\Core;
use FUTApi\FutError;
use Illuminate\Console\Command;
use Backpack\Settings\app\Models\Setting;
use Illuminate\Support\Facades\Log;

class RunBuy extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'buy:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the autobuyer';

    /**
     * The FUT API object
     *
     * @var array
     */
    protected $fut = [];

    /**
     * The Account Object
     *
     * @var array
     */
    protected $account = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }


    public function handle() {
        if (Setting::get('autobuyer_status') == '0') {
            $this->info("Autobuyer disabled.");
            return response(['status' => 403]);
        }
        $this->account = Accounts::where('status', '1')->where('in_use', '0')->whereNotNull('phishingToken')->first();
        if(!$this->account) {
            abort(403);
        }
        Accounts::find($this->account->id)->update([
            'in_use' => '1'
        ]);
        try {

            $this->fut = new Core(
                $this->account->email,
                $this->account->password,
                strtolower($this->account->platform),
                null,
                false,
                false,
                storage_path(
                    'app/fut_cookies/' . md5($this->account->email)
                )
            );
            $this->fut->setSession(
                $this->account->personaId,
                $this->account->nucleusId,
                $this->account->phishingToken,
                $this->account->sessionId,
                date("Y-m", strtotime($this->account->dob))
            );
            if($this->account->cooldown == 1) {
                if($this->account->cooldown_activated < Carbon::now()->subMinutes(10)->toDateTimeString()) {
                    Accounts::find($this->account->id)->update([
                        'phishingToken' => null,
                        'sessionId' => null,
                        'nucleusId' => null,
                        'cooldown' => '0',
                        'in_use' => '0',
                        'cooldown_activated' => new Carbon
                    ]);
                    abort(200);
                } else {
                    Accounts::find($this->account->id)->update([
                        'in_use' => '0'
                    ]);
                    abort(403);
                }
            } else {
                if($this->account->last_login < Carbon::now()->subMinutes(40)->toDateTimeString()) {
                    Accounts::find($this->account->id)->update([
                        'cooldown' => '1',
                        'in_use' => '0',
                        'cooldown_activated' => new Carbon
                    ]);
                    $this->fut->logout();
                    abort(403);
                }
            }
            $credits = $this->fut->keepalive();
            if($credits !== false) {
                $this->account->coins = $credits;
                $this->account->save();
                Accounts::find($this->account->id)->update([
                    'coins' => $credits
                ]);
            }

            Log::info('We have logged into '.$this->account->email.' & see that you have '.$credits.' left.');

            $this->sort_item_list();

            switch($this->account->platform) {
                case "XBOX":
                    $players = Players::query()->where('xb_buy_bin', '<', $credits)->where('xb_buy_bin', '!=', '0')->where('status', '1')->orderBy('last_searched', 'ASC')->get();
                    break;
                case "PS4":
                    $players = Players::query()->where('ps_buy_bin', '<', $credits)->where('ps_buy_bin', '!=', '0')->where('status', '1')->orderBy('last_searched', 'ASC')->get();
                    break;
            }

            if($players->count() === 0) {
                Accounts::find($this->account->id)->update([
                    'in_use' => '0'
                ]);
                abort(403);
            }

            $original_rpm_limit = Setting::get('rpm_limit');
            $bid_limit = Setting::get('player_limit');
            $rpm_limit = $original_rpm_limit/$players->count();
            $times = [];
            $time = 0;
            $sum_to = 60;
            while(array_sum($times) != $sum_to) {
                $times[$time] = mt_rand(1, $sum_to / mt_rand(1, 5));
                if (++$time == $original_rpm_limit) {
                    $time = 0;
                }
            }
            foreach($players as $player) {
                switch($this->account->platform) {
                    case "XBOX":
                        $buy_bin = $player->xb_buy_bin;
                        break;
                    case "PS4":
                        $buy_bin = $player->ps_buy_bin;
                        break;
                }
                Log::info('We are going to search for '.$player->name.' with a max BIN of '.$buy_bin);
                $cards_selling = Transactions::query()->where('player_id', $player->id)->where('account_id', $this->account->id)->where('sell_bin', '0')->get();
                if($cards_selling->count() >= $bid_limit) {
                    Accounts::find($this->account->id)->update([
                        'in_use' => '0'
                    ]);
                    abort(403);
                }
                if($this->account->tradepile_cards < 20){
                    if($this->account->coins > $buy_bin) {
                        if($buy_bin > 600){
                            //LETS SEARCH....
                            $sleep_time = array_pop($times);
                            $counter = 0;
                            $bids = 0;
                            $auctions = 0;
                            $auctionsWon = 0;
                            $auctionsFailed = 0;
                            Log::info('We passed all checks to search for '.$player->name.' with a max BIN of '.$buy_bin);
                            do {
                                if($bids >= $bid_limit) {
                                    break;
                                }
                                sleep($sleep_time);
                                $search = $this->fut->searchAuctions(
                                    'player',
                                    null,
                                    null,
                                    null,
                                    $player->resource_id,
                                    null,
                                    null,
                                    null,
                                    $buy_bin
                                );
                                Log::info('Finally, we searched for '.$player->name.' and found '.count($search['auctionInfo']).' auctions');
                                Log::info(print_r($search, true));
                                if(!empty($search['auctionInfo'])) {
                                    foreach($search['auctionInfo'] as $auction) {
                                        $bids++;
                                        $auctions++;
                                        try {
                                            $this->fut->tradeStatus($auction['tradeId']);
                                            $bid = $this->fut->bid($auction['tradeId'], $auction['buyNowPrice'], true);
                                            Log::info(print_r($bid, true));
                                            if(isset($bid['auctionInfo'])) {
                                                Log::notice('We won an auction for ' . $player->name . ' & bought him for ' . $auction['buyNowPrice']);
                                                $auctionsWon++;
                                                Transactions::insert([
                                                    'player_id' => $player->id,
                                                    'card_id' => $auction['itemData']['id'],
                                                    'buy_bin' => $auction['buyNowPrice'],
                                                    'account_id' => $this->account->id,
                                                    'platform' => $this->account->platform,
                                                    'bought_time' => new Carbon
                                                ]);
                                            } else {
                                                $auctionsFailed++;
                                            }
                                        } catch (FutError $e) {
                                            $error = $e->GetOptions();
                                            if($error['reason'] === 'permission_denied') {
                                                Log::notice('We failed to buy the auction for '.$player->name.' after attempting to buy him for '.$auction['buyNowPrice']);
                                                $auctionsFailed++;
                                            }
                                        }
                                    }
                                }
                                $counter++;
                            } while($counter < $rpm_limit);
                        }
                    }
                }
                Log::info('Search completed for '.$player->name.' with '.$counter.' total searches, '.$auctions.' auctions, winning '.$auctionsWon.' & losing '.$auctionsFailed);
                //update the player
                if($counter && $auctions && $auctionsWon && $auctionsFailed) {
                    $player->increment('total_searches', $counter);
                    $player->increment('auctions_found', $auctions);
                    $player->increment('auctions_won', $auctionsWon);
                    $player->increment('auctions_failed', $auctionsFailed);
                }
            }

        } catch(FutError $exception) {

            $error = $exception->GetOptions();

            if($error['reason'] !== 'permission_denied') {
                Accounts::find($this->account->id)->update([
                    'phishingToken' => null,
                    'sessionId' => null,
                    'nucleusId' => null,
                    'status_reason' => $error['reason'],
                    'in_use' => '0'
                ]);
            }

            Log::info('We caught an exception! '.$error['reason']);

            abort(403);

        }

        Accounts::find($this->account->id)->update([
            'in_use' => '0'
        ]);

        return ['status' => 200];
    }

    private function sort_item_list()
    {
        try {

            $items = $this->fut->unassigned();
            if(count($items['itemData']) > 0) {
                foreach($items['itemData'] as $item) {
                    $this->fut->sendToTradepile($item['id'], false);
                }
            }
            $tradepile = $this->fut->tradepile();
            $tradepile_value = 0;
            if(count($tradepile['auctionInfo']) == 0) {
                return false;
            } else {
                $this->account->tradepile_cards = count($tradepile['auctionInfo']);
            }
            foreach($tradepile['auctionInfo'] as $auction) {
                $trade = Transactions::where('card_id', $auction['itemData']['id'])->first();
                if(!$trade) {
                    continue;
                }
                switch($this->account->platform) {
                    case "XBOX":
                        $sell_bid = $trade->player->xb_sell_bid;
                        $sell_bin = $trade->player->xb_sell_bin;
                        break;
                    case "PS4":
                        $sell_bid = $trade->player->ps_sell_bid;
                        $sell_bin = $trade->player->ps_sell_bin;
                        break;
                }
                if($trade->sell_bin > 0) {
                    $tradepile_value = $tradepile_value + ($trade->sell_bin * 0.95);
                } else {
                    $tradepile_value = $tradepile_value + ($trade->listed_bin * 0.95);
                }
                if($auction['tradeState'] == NULL || $auction['tradeState'] == "expired"){
                    //lets sell
                    $sale = $this->fut->sell($trade->card_id, $sell_bid, $sell_bin);
                    $trade->listed_bin = $sell_bin;
                    $trade->listed_time = new Carbon;
                    $trade->save();
                    //check trade status
                    $this->fut->tradeStatus($sale['id']);
                } elseif($auction['tradeState'] == "closed"){
                    //we sold it!
                    $this->fut->removeSold($auction['tradeId']);
                    $trade->sell_bin = $auction['currentBid'];
                    $trade->sold_time = new Carbon;
                    $trade->save();
                }
            }
            $this->account->tradepile_value = $tradepile_value;
            $this->account->save();

        } catch(FutError $e) {

            $error = $e->GetOptions();
            Accounts::find($this->account->id)->update([
                'phishingToken' => null,
                'sessionId' => null,
                'nucleusId' => null,
                'status_reason' => $error['reason'],
                'in_use' => '0'
            ]);

            Log::info('We caught an exception in the sort item list! '.$error['reason']);

            abort(403);

        }
    }

}