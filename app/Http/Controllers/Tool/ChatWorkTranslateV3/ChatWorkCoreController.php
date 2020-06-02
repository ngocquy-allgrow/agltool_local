<?php

namespace App\Http\Controllers\Tool\ChatWorkTranslateV3;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;


class ChatWorkCoreController extends BaseController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function get_message_info($token, $room_id, $message_id){
        $url = 'https://api.chatwork.com/v2/rooms/'.$room_id.'/messages/'.$message_id;
        $result = $this->sendAPI_GET($token, $url, 'GET');        
        return $result;
    }

    public function get_room_info($token, $room_id){
        $url = 'https://api.chatwork.com/v2/rooms/' . $room_id . '/messages?force=0';
        $result = $this->sendAPI_GET($token, $url, 'GET');        
        return $result;
    }

    public function get_room($token, $room_id){
        $url = 'https://api.chatwork.com/v2/rooms/'.$room_id;
        $result = $this->sendAPI_GET($token, $url, 'GET');        
        return $result;
    }

    public function get_full_room_info($token, $room_id){
        $url = 'https://api.chatwork.com/v2/rooms/' . $room_id . '/messages?force=1';
        $result = $this->sendAPI_GET($token, $url, 'GET');
        return $result;
    }

    public function update_message($token, $room_id, $message_id, $body){
        $url = 'https://api.chatwork.com/v2/rooms/' . $room_id . '/messages/' . $message_id;
        $param = ['body' => $body];
        $result = $this->sendAPI_POST($token, $url, 'PUT', $param);
        return $result;
    }

    public function delete_message($token, $room_id, $message_id){
        $url = 'https://api.chatwork.com/v2/rooms/' . $room_id . '/messages/' . $message_id;
        $result = $this->sendAPI_DELETE($token, $url, 'DELETE');
        return $result;
    }

    public function create_message($token, $room_id, $body){
        $url = 'https://api.chatwork.com/v2/rooms/'.$room_id.'/messages';
        $param = ['body' => $body];
        $result = $this->sendAPI_POST($token, $url, 'POST', $param);
        return $result;
    }

    private function sendAPI_GET($token, $url, $method){
        $client = new Client();
        $jsContents = null;
        try {    
            $response = $client->request($method, $url, [
                'headers' =>[
                    'X-ChatWorkToken' => $token
                ], 
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

    private function sendAPI_POST($token, $url, $method, $param) {
        $client = new Client();
        $jsContents = null;
        try {
            $response = $client->request($method, $url, ['headers' => ['X-ChatWorkToken' => $token], 'form_params' => $param, 'http_errors' => false]);
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

    private function sendAPI_DELETE($token, $url, $method) {
        $client = new Client();
        $jsContents = null;
        try {
            $response = $client->request($method, $url, ['headers' => ['X-ChatWorkToken' => $token], 'http_errors' => false]);
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
