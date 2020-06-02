<?php
namespace App\Http\Controllers\Tool\SlackTranslateV1;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Tool\TranslationCore\TranslationCoreController;
use App\Http\Controllers\Tool\SlackTranslateV1\SlackCoreController;

use Auth;
// use App\User;
use App\Role;
use App\ToolSlackConfigs;
use App\ToolSlackEvents;

class SlackTranslateV1WebhookController extends BaseController {

	public function resgiter(Request $request) {
		$user_id = Auth::user()->id;
		$client = new Client();
		$response = $client->request('POST', 'https://slack.com/api/oauth.access', ['headers' => ['Content-Type' => 'application/x-www-form-urlencoded', ], 'form_params' => ["code" => $request->code, "client_id" => env('SLACK_CLIENT_ID') , "client_secret" => env('SLACK_CLIENT_SECRET') , ], 'http_errors' => false]);
		if (isset($response)) {
			$contents = $response->getBody()
				->getContents();
			$jsContents = json_decode($contents);
			if ($jsContents->ok == true) {
				$whereMath = array(
					'team_id' => $jsContents->team_id,
				);
				$toolSlackConfigs = ToolSlackConfigs::where($whereMath)->first();
				if (!$toolSlackConfigs) {
					$toolSlackConfigs = ToolSlackConfigs::firstOrCreate(array(
						'user_id' => $user_id,
						'user_slack_id' => $jsContents->user_id,
						'team_id' => $jsContents->team_id,
						'access_token' => $jsContents->access_token,
						'scope' => $jsContents->scope,
						'enterprise_id' => $jsContents->enterprise_id,
						'team_name' => $jsContents->team_name,
						'bot_user_id' => $jsContents
							->bot->bot_user_id,
						'bot_access_token' => $jsContents
							->bot->bot_access_token,
					));
					return redirect()
						->route('slack_translate_v1_index', ['notification' => 'Success register ' . $jsContents->team_name]);
				}
				else {
					if ($toolSlackConfigs->user_id == $user_id) {
						$toolSlackConfigs->update(array(
							'user_id' => $user_id,
							'user_slack_id' => $jsContents->user_id,
							'team_id' => $jsContents->team_id,
							'access_token' => $jsContents->access_token,
							'scope' => $jsContents->scope,
							'enterprise_id' => $jsContents->enterprise_id,
							'team_name' => $jsContents->team_name,
							'bot_user_id' => $jsContents
								->bot->bot_user_id,
							'bot_access_token' => $jsContents
								->bot->bot_access_token,
						));
						return redirect()
							->route('slack_translate_v1_index', ['warning' => 'Allready to register that team']);
					}
					else {
						return redirect()
							->route('slack_translate_v1_index', ['error' => 'Registered by other user']);
					}
				}
			}
			else {
				if ($jsContents->error == 'invalid_code') {
					return redirect()
						->route('slack_translate_v1_index', ['warning' => 'Access denied resgiter']);
				}
				return redirect()
					->route('slack_translate_v1_index', ['error' => $jsContents->error]);
			}
		}
	}

	public function webhookevent(Request $request) {
		$challenge = "cLsbvaedEdZavjnteBdaklWaj3V7BzVtQAc6jJON1PX6UbgXKN1L";
		// get data webhook
		$datas = json_decode($request->getContent() , true);

		//resgiter webhook event
		if ($datas['type'] === 'url_verification') {
			return $datas['challenge'];
		}

		// add event to log
		// if(Storage::exists('slack/log_event.txt')){
		// 	$content = Storage::get('slack/log_event.txt');
		// 	if($content != ''){
		// 		$content = ','.$content;
		// 	}
		// 	$content = $request->getContent().$content;
		// 	Storage::put('slack/log_event.txt', $content);
		// }else{
		// 	Storage::put('slack/log_event.txt', $request->getContent());
		// }

		// process webhook event
		$event = array(
			'event_ts' => $datas['event']['event_ts'],
			'token' => $datas['token'],
			'team_id' => $datas['team_id'],
			'api_app_id' => $datas['api_app_id'],
			'event' => json_encode($datas['event']) ,
			'type' => $datas['type'],
			'event_id' => $datas['event_id'],
			'event_time' => $datas['event_time'],
			'authed_users' => json_encode($datas['authed_users']) ,
		);
		$toolSlackEvents = ToolSlackEvents::where($event);
		if ($toolSlackEvents->count() == 0) {
			if ($datas['type'] === 'event_callback') {				
				// get bot information
				$user_slack_configs = new ToolSlackConfigs;
				$user_slack_configs = $user_slack_configs::where('team_id', $datas['team_id'])->first();
				if(!$user_slack_configs){ return $challenge; }

				$bot_user_id = $user_slack_configs->bot_user_id;
				$bot_access_token = $user_slack_configs->bot_access_token;

				// get language setting default
				$arrayLang = [];
				$setting_language = json_decode($user_slack_configs->setting_language, true);
				if(isset($datas['event']['channel'])){
					if (isset($setting_language[$datas['event']['channel']])) {
						if ($setting_language[$datas['event']['channel']] != "") {
							$arrayLang = explode(",", $setting_language[$datas['event']['channel']]);
						}
					}
				}				

				// progress event message
				if ($datas['event']['type'] == "message") {
					if (isset($datas['event']['subtype'])) {
						switch ($datas['event']['subtype']) {
							case 'thread_broadcast':								
								$toolSlackEvents = ToolSlackEvents::firstOrCreate($event);
								$toolSlackEvents->message = $datas['event']['text'];
								$text = $this->translate($arrayLang, $datas['event']['text'], $datas['team_id'], $datas['event']['channel']);
								if ($text) {									
									$toolSlackEvents->ts_bot = (new SlackCoreController)->reply_message($bot_access_token,$datas['event']['channel'],$datas['event']['ts'],$text)->ts;
								}
								$toolSlackEvents->save();
								break;
							case 'message_changed':
								if ($datas['event']['message']['type'] == "message") {
									if(isset($datas['event']['message']['subtype'])) {
										if ($datas['event']['message']['subtype'] == "tombstone") {
											$whereMath = array(
												'event_ts' => $datas['event']['message']['ts'],
											);
											$toolSlackEvents = ToolSlackEvents::where($whereMath)->orderBy('created_at', 'desc')->get();
											foreach ($toolSlackEvents as $key => $toolSlackEvent) {												
												$response =  (new SlackCoreController)->delete_message($bot_access_token,$datas['event']['channel'],$toolSlackEvent->ts_bot);
												if($response){
													$toolSlackEvent->delete();
												}																								
											}
										}										
									}else {
										$whereMath = array(
											'event_ts' => $datas['event']['previous_message']['ts'],
										);
										$toolSlackEvents = ToolSlackEvents::where($whereMath)->first();
										if($toolSlackEvents){
											$toolSlackEvents->message = $datas['event']['message']['text'];
											$text = $this->translate($arrayLang, $datas['event']['message']['text'], $datas['team_id'], $datas['event']['channel']);
											if ($text) {
												$response =  (new SlackCoreController)->update_message($bot_access_token,$datas['event']['channel'],$toolSlackEvents->ts_bot,$text);
											}
											$toolSlackEvents->save();
										}										
									}
								}
								break;
							case 'message_deleted':
								$whereMath = array(
									'event_ts' => $datas['event']['deleted_ts'],
								);
								$toolSlackEvents = ToolSlackEvents::where($whereMath)->orderBy('created_at', 'desc')->get();
								foreach ($toolSlackEvents as $key => $toolSlackEvent) {
									$response =  (new SlackCoreController)->delete_message($bot_access_token,$datas['event']['channel'],$toolSlackEvent->ts_bot);
									if($response){
										$toolSlackEvent->delete();
									}
								}								
								break;
							case 'channel_join':
								if ($datas['event']['user'] == $bot_user_id) {
									$setting_language[$datas['event']['channel']] = "";
									$user_slack_configs->setting_language = json_encode($setting_language);
									$user_slack_configs->save();
								}
								break;
							case 'group_join':
								if ($datas['event']['user'] == $bot_user_id) {
									$setting_language[$datas['event']['channel']] = "";
									$user_slack_configs->setting_language = json_encode($setting_language);
									$user_slack_configs->save();
								}
								break;
							case 'file_share':
								$toolSlackEvents = ToolSlackEvents::firstOrCreate($event);
								$toolSlackEvents->message = $datas['event']['text'];
								$text = $this->translate($arrayLang, $datas['event']['text'], $datas['team_id'], $datas['event']['channel']);
								if ($text) {									
									$toolSlackEvents->ts_bot = (new SlackCoreController)->reply_message($bot_access_token,$datas['event']['channel'],$datas['event']['ts'],$text)->ts;
								}
								$toolSlackEvents->save();
								break;
							default:																
								break;
						}
					}else{
						$toolSlackEvents = ToolSlackEvents::firstOrCreate($event);
						$toolSlackEvents->message = $datas['event']['text'];
						$toolSlackEvents->save();
						$text = $this->translate($arrayLang, $datas['event']['text'], $datas['team_id'], $datas['event']['channel']);
						if ($text) {							
							$toolSlackEvents->ts_bot = (new SlackCoreController)->reply_message($bot_access_token,$datas['event']['channel'],$datas['event']['ts'],$text)->ts;
						}
						$toolSlackEvents->save();
					}
				}
				elseif ($datas['event']['type'] == "channel_left" || $datas['event']['type'] == "group_left") {
					$setting_language = json_decode($user_slack_configs->setting_language, true);
					unset($setting_language[$datas['event']['channel']]);
					$user_slack_configs->setting_language = json_encode($setting_language);
					$user_slack_configs->save();
				}
				elseif ($datas['event']['type'] == "reaction_added") {
					$lang = $this->changeFlagToLang($datas['event']['reaction']);
					if($lang){
						$whereMath = array(
							'event_ts' => $datas['event']['item']['ts'],
						);
						$toolSlackEvents = ToolSlackEvents::where($whereMath)->first();
						if($toolSlackEvents){
							$toolSlackEvents1 = ToolSlackEvents::firstOrCreate($event);
							$toolSlackEvents1->event_ts = $datas['event']['item']['ts'];
							$toolSlackEvents1->message = $toolSlackEvents->message;
							$arrayLang = [];
							$arrayLang[] = $lang;
							$text = $toolSlackEvents->message;
							$text = $this->translate($arrayLang, $text, $datas['team_id'], $datas['event']['item']['channel']);
							if ($text) {								
								$toolSlackEvents1->ts_bot = (new SlackCoreController)->reply_message($bot_access_token,$datas['event']['item']['channel'],$datas['event']['item']['ts'],$text)->ts;
							}							
							$toolSlackEvents1->save();
						}						
					}					
				}
			}
		}

		// $content = Storage::get('slack/log_event.txt');			
		// $content = '{"event_id_callback":"'.$datas['event_id'].'"}'.','.$content;
		// Storage::put('slack/log_event.txt', $content);

		return $challenge;
	}

	private function translate($arrayLang, $text, $team_id, $channel) {
		$file_log_name = "slack/log/log_" . $team_id . "_" . $channel . ".txt";
		$text = strip_tags($text);
		if (count($arrayLang) > 0 && $text != "" && strlen(urlencode($text)) < 5000) {
			$result = '';
			// check not translation
			$re = "/(`((.)+?)`)|(:(((.+)[^-\s])+?):)/";
			$chars = preg_split($re, $text, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
			$arraykeyduplicate = array();
			foreach ($chars as $key => $value) {
				if ($key > 0) {
					if ($chars[$key] != "" && is_string($chars[$key])) {
						if (strpos($chars[$key - 1], $chars[$key]) !== false) {
							array_push($arraykeyduplicate, $key);
						}
					}
					else {
						array_push($arraykeyduplicate, $key);
					}
				}
			}
			foreach ($arraykeyduplicate as $value) {
				unset($chars[$value]);
			}
			// detect language
			$text_detect = '';
			foreach ($chars as $key => $value) {
				if (substr($value, 0, 1) == '`' && substr($value, -1) == '`') {
				}
				elseif (substr($value, 0, 1) == ':' && substr($value, -1) == ':') {
				}
				else {
					$text_detect .= $value;
				}
			}
			$olang = (new TranslationCoreController)->detect($text_detect);

			// translation
			if (trim($text_detect) != '') {
				foreach ($arrayLang as $key => $tlang) {
					if ($tlang != $olang) {
						if ($result != '') {
							$result .= "\n\n";
						}
						$result .= $this->changeIconFlag($tlang) . " ";
						foreach ($chars as $key => $value) {
							if (substr($value, 0, 1) == '`' && substr($value, -1) == '`') {
								$result .= $value;
							}
							elseif (substr($value, 0, 1) == ':' && substr($value, -1) == ':') {
								$result .= $value;
							}
							else {
								Storage::append($file_log_name, $value);
								if (substr($value, 0, 1) == " ") {
									$result .= substr($value, 0, 1);
									$value = substr($value, 1);
								}
								if (substr($value, 0, 1) == "\n") {
									$result .= substr($value, 0, 1);
									$value = substr($value, 1);
								}
								if(trim($value) != ""){									
									$translated_text = (new TranslationCoreController)->translate_system($olang,$tlang,$value);
									if($translated_text){
										$result .= $translated_text;
									}									
								}															

								if (substr($value, -1) == " ") {
									$result .= substr($value, -1);
								}
								if (substr($value, -1) == "\n") {
									$result .= substr($value, -1);
								}
							}
						}
					}
				}
				if($result != ''){
					return $result;
				}else{
					return null;
				}				
			}else{
				return null;
			}
		}
		return null;
	}

	private function changeIconFlag($lang) {
		switch ($lang) {
			case "vi":
				return ':flag-vn:';
			break;
			case "ja":
				return ':jp:';
			break;
			case "en":
				return ':us:';
			break;
			case "it":
				return ':flag-lt:';
			break;
			default:
				return null;
		}
	}

	private function changeFlagToLang($icon) {
		switch ($icon) {
			case "flag-vn":
				return 'vi';
			break;
			case "jp":
				return 'ja';
			break;
			case "us":
				return 'en';
			break;
			case "flag-lt":
				return 'it';
			break;
			default:
				return null;
		}
	}
}

