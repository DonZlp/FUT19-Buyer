<?php
/**
 * Created by PhpStorm.
 * User: curtiscrewe
 * Date: 19/10/2018
 * Time: 00:47
 */

namespace App\Console\Commands;

use App\Models\Accounts;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Backpack\Settings\app\Models\Setting;

use FUTApi\Core;
use FUTApi\FutError;

class FixAccounts extends Command {

    protected $signature = 'fix_accounts:cron';

    protected $description = 'Fix any offline accounts.';

    public function __construct() {
        parent::__construct();
    }

    public function handle()
    {
        if (Setting::get('autobuyer_status') == '0') {
            $this->info("Autobuyer disabled.");
            return;
        }
        $accounts = Accounts::where('status', '1')->whereNull('phishingToken')->orderByRaw("RAND()")->get();
        foreach($accounts as $account) {
            $account = Accounts::find($account->id);
            $backup_codes = explode(',', trim($account->backup_codes));
            $this->info("Logging into " . $account->email . " to update the session.");
            try {
                $fut = new Core(
                    $account->email,
                    $account->password,
                    strtolower($account->platform),
                    $backup_codes[array_rand($backup_codes, 1)],
                    false,
                    false,
                    storage_path(
                        'app/fut_cookies/'.md5($account->email)
                    )
                );
                $login = $fut->login();

                //login successful.
                $account->phishingToken = $login['auth']['phishing_token'];
                $account->personaId = $login['mass_info']['userInfo']['personaId'];
                $account->personaName = $login['mass_info']['userInfo']['personaName'];
                $account->nucleusId = $login['auth']['nucleus_id'];
                $account->clubName = $login['mass_info']['userInfo']['clubName'];
                $account->sessionId = $login['auth']['session_id'];
                $account->coins = $login['mass_info']['userInfo']['credits'];
                $account->tradepile_limit = $login['mass_info']['pileSizeClientData']['entries'][0]['value'];
                $account->dob = $login['auth']['dob'];
                $account->last_login = new Carbon;
                $account->status_reason = null;
                $account->save();

                $this->info("We updated ".$account->email." successfully!");

            }catch(FutError $exception) {
                $error = $exception->GetOptions();
                $account->status = '-1';
                $account->status_reason = $error['reason'];
                $account->last_login = new Carbon;
                $account->save();
                $this->info("Error ".$error['reason']." on account: ".$account->email);
            }
        }
    }

}