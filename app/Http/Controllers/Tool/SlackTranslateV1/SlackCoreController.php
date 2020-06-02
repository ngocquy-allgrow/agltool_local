<?php

namespace App\Http\Controllers\Tool\SlackTranslateV1;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class SlackCoreController extends BaseController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function get_team_info($bot_access_token){
        $team_info = $this->sendAPI_GET  ('https://slack.com/api/team.info','GET', [
                                'token' => $bot_access_token,
                            ]);
        if($team_info){
            return $team_info;
        }
        return null;
    }

    public function get_conversation_info($bot_access_token,$channel){
        $channels_info = $this->sendAPI_GET  ('https://slack.com/api/conversations.info','GET', [
                                'token' => $bot_access_token,
                                'channel' => $channel
                            ]);
        if($channels_info->ok){
            return $channels_info->channel;
        }        
        return null;
    }

    public function get_channel_info($bot_access_token,$channel){
        $channels_info = $this->sendAPI_GET  ('https://slack.com/api/channels.info','GET', [
                                'token' => $bot_access_token,
                                'channel' => $channel
                            ]);
        if($channels_info->ok){
            return $channels_info->channel;
        }
        return null;
    }

    public function get_group_info($bot_access_token,$channel){
        $groups_info = $this->sendAPI_GET  ('https://slack.com/api/groups.info','GET', [
                                'token' => $bot_access_token,
                                'channel' => $channel
                            ]);
        if($groups_info->ok){
            return $groups_info->group;
        }
        return null;
    }

    public function reply_message($bot_access_token,$channel,$thread_ts,$text){
        $param = ["token" => $bot_access_token, "channel" => $channel, "thread_ts" => $thread_ts, "text" => $text];
        $response = $this->sendAPI_POST('https://slack.com/api/chat.postMessage', 'POST', $param);
        if ($response) {
            return $response;
        }
        return null;
    }

    public function update_message($bot_access_token,$channel,$ts,$text){
        $param = ["token" => $bot_access_token, "channel" => $channel, "ts" => $ts, "text" => $text];
        $response = $this->sendAPI_POST('https://slack.com/api/chat.update', 'POST', $param);
        if ($response) {
            return $response;
        }
        return null;
    }

    public function delete_message($bot_access_token,$channel,$ts){
        $param = ["token" => $bot_access_token, "channel" => $channel, "ts" => $ts];
        $response = $this->sendAPI_POST('https://slack.com/api/chat.delete', 'POST', $param);
        if ($response) {
            return $response;
        }
        return null;
    }

    private function sendAPI_GET($url,$method,$param){
        $client = new Client();
        $jsContents = null;
        try {    
            $response = $client->request($method, $url, [
                'headers' =>[
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ], 
                'query' => $param,
                'http_errors' => false
            ]);           
        } catch (GuzzleHttp\Exception\ClientException $e) {
            $e_response = $e->getResponse();
            $responseBodyAsString = $e_response->getBody()->getContents();
        } finally {
            if(isset($response)){
                if($response->getStatusCode() == 200){
                    $contents = $response->getBody()->getContents();         
                    $jsContents = json_decode($contents);
                }
            }
            return $jsContents;
        }
    }

    private function sendAPI_POST($url, $method, $param) {
        $client = new Client();
        $jsContents = null;
        try {
            $response = $client->request($method, $url, ['headers' => ['Content-Type' => 'application/x-www-form-urlencoded', ], 'form_params' => $param, 'http_errors' => false]);
        } catch (GuzzleHttp\Exception\ClientException $e) {
            $e_response = $e->getResponse();
            $responseBodyAsString = $e_response->getBody()->getContents();
        } finally {
            if(isset($response)){
                if($response->getStatusCode() == 200){
                    $contents = $response->getBody()->getContents();         
                    $jsContents = json_decode($contents);
                }
            }
            return $jsContents;
        }
    }

}
