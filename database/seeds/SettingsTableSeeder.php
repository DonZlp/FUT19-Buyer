<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * The settings to add.
     */
    protected $settings = [
        [
            'key'           => 'rpm_limit',
            'name'          => 'RPM Limit',
            'description'   => 'Max requests per minute to the EA servers',
            'value'         => '5',
            'field'         => '{"name":"value","label":"Value", "title":"RPM Limit" ,"type":"textarea"}',
            'active'        => 1,
        ],
        [
            'key'           => 'autobuyer_status',
            'name'          => 'Autobuyer Status',
            'description'   => 'Enable or Disable the Autobuyer',
            'value'         => '1',
            'field'         => '{"name":"value","label":"Value", "title":"Autobuyer Status" ,"type":"select_from_array", "options":{"0":"Disabled","1":"Enabled"}}',
            'active'        => 1,
        ],
        [
            'key'           => 'price_update_interval',
            'name'          => 'Price Update Interval',
            'description'   => 'Interval in minutes when player prices are updated',
            'value'         => '10',
            'field'         => '{"name":"value","label":"Value", "title":"Price Update Interval" ,"type":"textarea"}',
            'active'        => 1,
        ],
        [
            'key'           => 'player_limit',
            'name'          => 'Player Limit',
            'description'   => 'Max amount of a player purchased per account',
            'value'         => '5',
            'field'         => '{"name":"value","label":"Value", "title":"Player Limit" ,"type":"textarea"}',
            'active'        => 1,
        ],
        [
            'key'           => 'buy_percentage',
            'name'          => 'Buy Percentage',
            'description'   => 'Percentage of which the lowest BIN we\'ll buy for',
            'value'         => '91',
            'field'         => '{"name":"value","label":"Value", "title":"Buy Percentage" ,"type":"textarea"}',
            'active'        => 1,
        ],
        [
            'key'           => 'sell_percentage',
            'name'          => 'Sell Percentage',
            'description'   => 'Percentage of which we\'ll sell for',
            'value'         => '98',
            'field'         => '{"name":"value","label":"Value", "title":"Sell Percentage" ,"type":"textarea"}',
            'active'        => 1,
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->settings as $index => $setting) {
            $result = DB::table('settings')->insert($setting);

            if (!$result) {
                $this->command->info("Insert failed at record $index.");

                return;
            }
        }

        $this->command->info('Inserted '.count($this->settings).' records.');
    }
}
