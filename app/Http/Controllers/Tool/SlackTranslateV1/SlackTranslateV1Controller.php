<?php

namespace App\Http\Controllers\Tool\SlackTranslateV1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

use Auth;
use App\ToolSlackConfigs;
use App\ToolSlackEvents;

use App\Http\Controllers\Tool\SlackTranslateV1\SlackCoreController;

class SlackTranslateV1Controller extends BaseController
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
        $bot_users = ToolSlackConfigs::where('user_id', $user_id)->get();
        
        $result = array();
        foreach ($bot_users as $key => $bot_user) {
            $team_info = (new SlackCoreController)->get_team_info($bot_user->bot_access_token);
            if(!isset($team_info->team)) continue;

            $result[$key]['team'] = $team_info->team;
            $result[$key]['groups'] = array();
            if($bot_user->setting_language){
                $setting_language = json_decode($bot_user->setting_language,true);
                foreach ($setting_language as $key2 => $value) {
                   $conversations_info = (new SlackCoreController)->get_conversation_info($bot_user->bot_access_token,$key2);
                    if(!isset($conversations_info)) continue;
                    
                    $result[$key]['conversations_info'][$key2] = $conversations_info;
                    $result[$key]['groups'][$key2] = explode(",",$value);
                }
            }
        }

        return view('tool/slack_translate_v1/detail', [
            'datas' => $result
        ]);
        
    }

    public function edit(Request $request){
        $request->user()->authorizeRoles(['admin', 'customer', 'staff']);

        $checkbox1 = isset($request->checkbox1)?$request->checkbox1:'';
        $checkbox2 = isset($request->checkbox2)?','.$request->checkbox2:'';
        $checkbox3 = isset($request->checkbox3)?','.$request->checkbox3:'';
        $arrayLang = $checkbox1.$checkbox2.$checkbox3;
        $firstCharacter = substr($arrayLang, 0, 1);
        if($firstCharacter == ','){
             $arrayLang = substr($arrayLang, 1);
        }

        $channel_id = $request->channel_id;
        $whereMath = array(
                        'user_id' => Auth::user()->id,
                        'team_id' => $request->team_id,
                    );
        $toolSlackConfigs = ToolSlackConfigs::where($whereMath)->first();
        if ($toolSlackConfigs){
            $setting_language = json_decode($toolSlackConfigs->setting_language,true);
            $setting_language[$channel_id] = $arrayLang;
            $toolSlackConfigs->setting_language = json_encode($setting_language);
            $toolSlackConfigs->save();
           return redirect()
                    ->route('slack_translate_v1_index', ['notification' => 'Success edit '.$channel_id]);
        }else{
            return redirect()
                    ->route('slack_translate_v1_index', ['error' => 'Can not edit']);
        }   
    }

    public function delete(Request $request){
        $request->user()->authorizeRoles(['admin', 'customer', 'staff']);
        $whereMath = array(
                        'user_id' => Auth::user()->id,
                        'team_id' => $request->team_id,
                    );
        $toolSlackConfigs = ToolSlackConfigs::where($whereMath)->first();
        if ($toolSlackConfigs){
            $toolSlackConfigs->delete();
            return redirect()
                    ->route('slack_translate_v1_index', ['notification' => 'Success delete '.$request->team_id]);
        }else{
            return redirect()
                    ->route('slack_translate_v1_index', ['error' => 'Can not edit']);
        } 
    }

    public function admin(Request $request)
    {
        $request->user()->authorizeRoles(['admin']);

        $bot_users = ToolSlackConfigs::all();
        
        $result = array();
        foreach ($bot_users as $key => $bot_user) {
            $team_info = (new SlackCoreController)->get_team_info($bot_user->bot_access_token);
            if(!isset($team_info->team)) continue;
            
            $result[$key]['team'] = $team_info->team;
            $result[$key]['groups'] = array();
            if($bot_user->setting_language){
                $setting_language = json_decode($bot_user->setting_language,true);
                foreach ($setting_language as $key2 => $value) {
                    $conversations_info = (new SlackCoreController)->get_conversation_info($bot_user->bot_access_token,$key2);
                    if(!isset($conversations_info)) continue;
                    
                    $result[$key]['conversations_info'][$key2] = $conversations_info;
                    $result[$key]['groups'][$key2] = explode(",",$value);
                    $file_log_name = "slack/log/log_".$result[$key]['team']->id."_".$key2.".txt";
                    $exists = Storage::has($file_log_name);
                    if($exists){
                        $content = Storage::get($file_log_name);
                        $char_num = mb_strlen($content);
                        $result[$key]['log'][$key2] = $char_num;
                    }else{
                        $result[$key]['log'][$key2] = 0;
                    }
                }
            }
        }

        return view('admin/manager_slack', [
            'datas' => $result
        ]);
        
    }

    public function edit_admin(Request $request){
        $request->user()->authorizeRoles(['admin']);

        $checkbox1 = isset($request->checkbox1)?$request->checkbox1:'';
        $checkbox2 = isset($request->checkbox2)?','.$request->checkbox2:'';
        $checkbox3 = isset($request->checkbox3)?','.$request->checkbox3:'';
        $arrayLang = $checkbox1.$checkbox2.$checkbox3;
        $firstCharacter = substr($arrayLang, 0, 1);
        if($firstCharacter == ','){
             $arrayLang = substr($arrayLang, 1);
        }

        $channel_id = $request->channel_id;
        $whereMath = array(
                        'team_id' => $request->team_id,
                    );
        $toolSlackConfigs = ToolSlackConfigs::where($whereMath)->first();
        if ($toolSlackConfigs){
            $setting_language = json_decode($toolSlackConfigs->setting_language,true);
            $setting_language[$channel_id] = $arrayLang;
            $toolSlackConfigs->setting_language = json_encode($setting_language);
            $toolSlackConfigs->save();
           return redirect()
                    ->route('manager_slack', ['notification' => 'Success edit '.$channel_id]);
        }else{
            return redirect()
                    ->route('manager_slack', ['error' => 'Can not edit']);
        }   
    }

    public function delete_admin(Request $request){
        $request->user()->authorizeRoles(['admin']);
        $whereMath = array(
                        'team_id' => $request->team_id,
                    );
        $toolSlackConfigs = ToolSlackConfigs::where($whereMath)->first();
        if ($toolSlackConfigs){
            $toolSlackConfigs->delete();
            return redirect()
                    ->route('manager_slack', ['notification' => 'Success delete '.$request->team_id]);
        }else{
            return redirect()
                    ->route('manager_slack', ['error' => 'Can not edit']);
        } 
    }

    public function readlog(){
        if(Storage::exists('slack/log_event.txt')){
            $content = Storage::get('slack/log_event.txt');
            if(isset($content)){
                return '['.$content.']';
            }
        }        
        return null;
    }
        
}
