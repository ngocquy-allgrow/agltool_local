<?php

namespace App\Http\Controllers\Tool\ChatWorkTranslateV3;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;


use Auth;
use App\ToolChatworkConfigs;

class ChatWorkTranslateV3Controller extends BaseController
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $request->user()->authorizeRoles(['admin', 'customer', 'staff']);

        $user_id = Auth::user()->id;
        $user_config = ToolChatworkConfigs::where('user_id', $user_id)->first();

        if($user_config){
            return redirect()->route('chatwork_translate_v3_detail');
        }else{
            return view('tool/chatwork_translate_v3/index');
        }
    }

    public function detail(Request $request){
        $request->user()->authorizeRoles(['admin', 'customer', 'staff']);

        $user_id = Auth::user()->id;
        $user_config = ToolChatworkConfigs::where('user_id', $user_id)->first();

        $client = new Client();
        $response = $client->request('GET', 'https://api.chatwork.com/v2/rooms', [
            'headers' => [
                'X-ChatWorkToken' => $user_config->token
            ],
            'http_errors' => false
        ]);
        if($response){
            if($response->getStatusCode() == 200){

                $contents = $response->getBody()->getContents();
                return view('tool/chatwork_translate_v3/detail', [
                    'datas'           => json_decode($contents, true),
                    'user_config' => $user_config
                ]);
            }
        }
        
    }

    public function register(Request $request)
    {
        request()->validate([
            'token' => 'required',
        ]);

        $user_id = Auth::user()->id;
        $user_config = ToolChatworkConfigs::where('token', $request->input('token'))->first();
        if($user_config){
            return back()->withErrors(['token' => ['This token is used by other account']]);
        }

        $client = new Client();
        $response = $client->request('GET', 'https://api.chatwork.com/v2/me', [
            'headers' => [
                'X-ChatWorkToken' => $request->input('token')
            ],
            'http_errors' => false
        ]);
        if(!$response){
            return back()->withErrors(['token' => ['Error token']]);
        }
        if($response->getStatusCode() == 200){
            $contents = $response->getBody()->getContents();
            $data = json_decode($contents, true);
            $this->saveAccount($data['account_id'],$data['name'],$request->input('token'));
            return redirect()->route('chatwork_translate_v3_detail');
        }else{
            return back()->withErrors(['token' => ['Error token']]);
        }
    }

    public function updateRoomChatworkAccount(Request $request){
        $user_config = ToolChatworkConfigs::where('user_id',  $request->input('user_id'))->first();
        $user_config->update([
            'room_id_array' => $request->input('room_id_array'),
       ]);

        return response()->json([
            'code' => '200',
            'state' => 'ok'
        ]);
    }

    public function logout(){
        $user_id = Auth::user()->id;
        $user_config = ToolChatworkConfigs::where('user_id', $user_id)->first();
        if(!is_null($user_config)){
            $user_config->delete();
            return redirect()->route('chatwork_translate_v3_index');
        }
    }

    private function saveAccount($account_id, $account_name, $token){
        $user_id = Auth::user()->id;
        $user_config = ToolChatworkConfigs::where('user_id', $user_id)->first();
        if(is_null($user_config)){
            ToolChatworkConfigs::create([
                'user_id' => $user_id,
                'account_id' => $account_id,
                'account_name' => $account_name,
                'room_id_array' => '',
                'token' => $token,
            ]);
        }else{
            $user_config->update([
                'account_id' => $account_id,
                'account_name' => $account_name,               
                'room_id_array' => '',
                'token' => $token,
            ]);
        }   
    }
}
