<?php
namespace App\Helpers;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class BOT{
    private static $bot;
    /*public static function getInstance(){
      $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient('1542354965>');
      self::$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => 'xxx']);
      return self::$bot;
    }*/
    public static function makeRequest($method, $url, $data=array()){
      $client = new Client(['base_uri' => 'https://api.line.me/v2/bot/']);
      $data['headers'] = ['Authorization' => 'Bearer f/Nd/Zw1j+tjbkmxvxCAsNr4MutVu5WBcR+ZG2EcchZ9fycAnG6lmlnZ91X5zdqhy9Bacp7nnmuz/vhaQ+gnzf7qp3gRM+Lrb/T+X55L4AuwwXScOWsniieVu8N+oC8a3dMvxhL+M1QA09LX1F1GHQdB04t89/1O/w1cDnyilFU='];
      return json_decode((string)($client->request($method, $url, $data)->getBody()));
    }
}