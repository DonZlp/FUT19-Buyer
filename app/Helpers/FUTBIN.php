<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class FUTBIN {

    /**
     * Access URL for the private FUTBIN API
     * @var (new FUTBIN)->$api_url
     */
    private $api_url = "https://www.futbin.org/";

    /**
     * Selected console for which data is returned
     * @var (new FUTBIN)->$console
     */
    private $console = null;

    /**
     * Selected console for which data is returned
     * @object (new FUTBIN)->$client
     */
    private $client = null;

    /**
     * Array of the data that's returned for future access
     * @array (new FUTBIN)->$requestCache
     */
    private $requestCache = null;

    public function __construct($console) {
        if(in_array($console, array("XBOX", "PS", "PS4", "PC"))) {
            if($console == "XBOX") {
                $this->console = "XB";
            } elseif($console == "PS4") {
                $this->console = "PS";
            } else {
                $this->console = $console;
            }
        }
        $this->setupClient();
    }

    public function apiStatus() {
        $response = $this->client->request('GET', 'iosstatus.json?'.time(), [
            'headers' => [
                'User-Agent' => 'Futbin/2 (iPhone; iOS 9.2.1; Scale/2.00)',
            ]
        ]);
        return json_decode($response->getBody(), true);
    }

    public function webPlayers($name = null) {
        try {
            $response = $this->client->request('GET', 'http://www.futbin.com/search?year=19&term='.$name);
        } catch (ClientException $e) {
            if ($e->hasResponse()) {
                return json_decode($e->getResponse(), true);
            }
        }
        return json_decode($response->getBody(), true);
    }

    public function getPlayers($name = null) {
        try {
            $response = $this->client->request('POST', 'futbin/api/searchPlayersByName', [
                'form_params' => [
                    'playername' => $name
                ]
            ]);
        } catch (\Exception $e) {
            if ($e->hasResponse()) {
                return json_decode($e->getResponse(), true);
            }
        }
        return json_decode($response->getBody(), true);
    }

    public function selectPlayer($id = null, $resourceId = null, $proxy = false) {
        if($proxy !== false) {
            $proxy = $this->getProxy();
        }
        try {
            $response = $this->client->request('POST', 'futbin/api/fetchPlayerInformation', [
                'form_params' => [
                    'ID' => $id,
                    'platform' => $this->console
                ],
                'proxy' => (isset($proxy['ip']) ? 'tcp://'.$proxy['ip'].':'.$proxy['port'] : null)
            ]);
        } catch (ClientException $response) {
            return false;
        }
        $this->requestCache = json_decode($response->getBody(), true);
        if($resourceId) {
            if($this->requestCache['errorcode'] == "200") {
                if(count($this->requestCache['data']) > 0) {
                    foreach($this->requestCache['data'] as $player) {
                        if($player['Player_Resource'] == $resourceId) {
                            $this->requestCache = array(
                                "data" => array($player),
                                "errorcode" => "200",
                                "errormsg" => "SUCCESS"
                            );
                        }
                    }
                }
            }
        }
        return $this->requestCache;
    }

    public function selectVersion($rating = null) {
        if($rating) {
            if($this->requestCache['errorcode'] == "200") {
                foreach($this->requestCache['data'] as $player) {
                    if($player['Player_Rating'] == $rating) {
                        $this->requestCache = array(
                            "data" => array($player),
                            "errorcode" => "200",
                            "errormsg" => "SUCCESS"
                        );
                        return true;
                    }
                }
            }
        }
        return array(
            "errorcode" => "400",
            "errormsg" => "ERROR - Rating not found!"
        );
    }

    public function getVersions() {
        $versions = array();
        if($this->requestCache['errorcode'] == "200") {
            foreach($this->requestCache['data'] as $player) {
                $versions[] = array(
                    "baseId" => $player['Player_ID'],
                    "resourceId" => $player['Player_Resource'],
                    "type" => $player['Revision'],
                    "rating" => $player['Player_Rating']
                );
            }
        }
        return $versions;
    }

    public function getLowestBIN() {
        if($this->requestCache['errorcode'] == "200") {
            if(count($this->requestCache['data']) == 1) {
                return array(
                    "lowest_bin" => $this->requestCache['data'][0]['LCPrice']
                );
            } else {
                return array(
                    "errorcode" => "400",
                    "errormsg" => "ERROR - Multiple Players Found"
                );
            }
        }
    }

    public function getPriceRanges() {
        if($this->requestCache['errorcode'] == "200") {
            if(count($this->requestCache['data']) == 1) {
                return array(
                    "start_range" => $this->requestCache['data'][0]['MinPrice'],
                    "end_range" => $this->requestCache['data'][0]['MaxPrice']
                );
            } else {
                return array(
                    "errorcode" => "400",
                    "errormsg" => "ERROR - Multiple Players Found"
                );
            }
        }
    }

    private function getProxy()
    {
        $url = 'https://api.getproxylist.com/proxy?protocol[]=http&allowsHttps=1&maxConnectTime=1';
        $request = @file_get_contents($url);
        return json_decode($request, true);
    }

    private function setupClient() {
        $this->client = new Client([
            'base_uri' => $this->api_url,
            'http_errors' => false,
            'verify' => false,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; rv:49.0) Gecko/20100101 Firefox/49.0',
            ]
        ]);
    }

}