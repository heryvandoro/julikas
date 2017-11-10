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
      $body['headers'] = ['Authorization' => 'Bearer f/Nd/Zw1j+tjbkmxvxCAsNr4MutVu5WBcR+ZG2EcchZ9fycAnG6lmlnZ91X5zdqhy9Bacp7nnmuz/vhaQ+gnzf7qp3gRM+Lrb/T+X55L4AuwwXScOWsniieVu8N+oC8a3dMvxhL+M1QA09LX1F1GHQdB04t89/1O/w1cDnyilFU='];
      $body['json'] = $data;
      $result = $client->request($method, $url, $body);
      try{
        return json_decode((string)($result->getBody()));
      }catch(Exception $e){
        return response()->json(["status"=>"success"]);
      }
    }
  
    public static function replyMessages($replyToken, $messages){
       self::makeRequest("POST", "message/reply", [
         "replyToken" => $replyToken,
         "messages" => $messages
       ]);
    }
  
    public static function pushMessages($to, $messages){
       self::makeRequest("POST", "message/push", [
         "to" => $to,
         "messages" => $messages
       ]);
    }
  
    public static function getProfile($user_id){
       return self::makeRequest("GET", "profile/".$user_id);
    }
    public static function getGroupMemberProfile($group_id, $user_id){
       return self::makeRequest("GET", "group/".$group_id."/member/".$user_id);
    }
}