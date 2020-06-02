<?php

namespace App\Http\Controllers\Tool\TranslationCore;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;


use App\TranslationSystemInfos;

class TranslationCoreController extends BaseController
{
	
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function translate_system2($olang, $arrayLang, $text){
    	if( $text == '' ) return null;

    	$arrayString = null;
    	if($olang != null){
    		foreach ($arrayLang as $key => $value) {
	            if($key != 0)  {
	                if(isset($tlang)){
	                    $tlang .= ',"'.$value.'"';
	                }else{
	                    $tlang = '"'.$value.'"';
	                }                
	            }
	        }

	        $text = str_replace('%', '&#37;', $text);
	        $text = str_replace(' ', '%20', $text);
	        $url = "https://translationgooglenodejs.herokuapp.com/translation/".urlencode($text)."/[".$tlang."]/".$olang;
	        $jsContents = $this->sendAPI_GET($url);
	        if($jsContents){
	        	if (isset($jsContents->result)){
	        		foreach ($jsContents->result as $key => $value) {
	        			$arrayString[] = $value->text;
	        		}
	        	}
	        }
    	}
    	
    	return $arrayString;
    }

    public function translate_system($olang, $tlang, $text){
    	if( $text == '' ) return null;
    	$string = null;

		$translationSystemInfos = TranslationSystemInfos::where('status', '!=' , 1)->where('name', '!=' , 'yandex')->orderBy('count', 'ASC')->get();

		$chooseSystem = null;
		foreach ($translationSystemInfos as $translationSystemInfo) {
			if($translationSystemInfo->error_at == null){
				$chooseSystem = $translationSystemInfo;
				break;
			}else{
				$time_error = strtotime(now()) - strtotime($translationSystemInfo->error_at);
				if($time_error > 5*60){
					$chooseSystem = $translationSystemInfo;
					break;
				}
			}
		}

		if($chooseSystem != null){
			$string = $this->system_info('translate', $chooseSystem, $text, $olang, $tlang);
		}else{
			return null;
			usleep( 4000000 );
		}

		if(!$string){
			//usleep( 1000000 );
			$string = $this->translate_system($olang, $tlang, $text);
		}
		
       	return $string;
    }

    public function detect($text){
    	if( $text == '' ) return null;
    	$olang = null;

    	$translationSystemInfos = TranslationSystemInfos::where('status', '!=' , 1)->where('name', '!=' , 'yandex')->orderBy('count', 'ASC')->get();

		$chooseSystem = null;
		foreach ($translationSystemInfos as $translationSystemInfo) {
			if($translationSystemInfo->error_at == null){
				$chooseSystem = $translationSystemInfo;
				break;
			}else{
				$time_error = strtotime(now()) - strtotime($translationSystemInfo->error_at);
				if($time_error > 5*60){
					$chooseSystem = $translationSystemInfo;
					break;
				}
			}
		}
		if($chooseSystem != null){
			$olang = $this->system_info('detect', $chooseSystem, $text);
		}

		if (!$olang || $olang == '') {
			$olang = 'auto';
		}
		return $olang;
    }

    private function system_info($type, $translationSystemInfos, $text, $olang=null, $tlang=null){
    	$result = null;    	

		if($translationSystemInfos){
			$translationSystemInfos->status = 1;
			$translationSystemInfos->save();
			$root_url = $translationSystemInfos->url;
			try {
				switch ($translationSystemInfos->name) {
		    		case 'root_m':
		    			if($type == "detect") $result = $this->detectLanguageGoogle($text);
		    			if($type == "translate") $result = $this->translateGoogle($olang, $tlang, $text);
		    			break;

		    		case 'agl-test_m':
		    			if($type == "detect") $result = $this->detectLanguageGoogle_000host($text, $root_url);
		    			if($type == "translate") $result = $this->translateGoogle_000host($olang, $tlang, $text, $root_url);
		    			break;
		    		
		    		case 'google1_m':	    			
		    			if($type == "detect") $result = $this->detectLanguageGoogle_000host($text, $root_url);
		    			if($type == "translate") $result = $this->translateGoogle_000host($olang, $tlang, $text, $root_url);
		    			break;

		    		case 'google2_m':
		    			if($type == "detect") $result = $this->detectLanguageGoogle_000host($text, $root_url);
		    			if($type == "translate") $result = $this->translateGoogle_000host($olang, $tlang, $text, $root_url);
		    			break;

		    		default:
		    			$result = null;
		    			break;
		    	}
		    } catch (Exception $e) {
				$translationSystemInfos->note = $e->getMessage();
            	$result = null;
            }
			if($result){
				$translationSystemInfos->char_length += mb_strlen($text);
				$translationSystemInfos->count += 1;
				$translationSystemInfos->error_at = null;
			}else{
				$translationSystemInfos->count += 1;
				$translationSystemInfos->error_num += 1;
				$translationSystemInfos->error_at = now();
			}			
			$translationSystemInfos->status = 0;
			$translationSystemInfos->save();		
		}
		return $result;
    }

    private function detectLanguageGoogle($text) {
		$olang = 'auto';
		$tlang = 'en';
		$url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=" . $olang . "&tl=" . $tlang . "&dt=t&q=" . urlencode($text);
		$jsContents = $this->sendAPI_GET($url);
		if($jsContents){
			if (isset($jsContents[2])) {
				return $jsContents[2];
			}
		}
		return null;
	}

	private function translateGoogle($olang, $tlang, $text) {
		$url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=" . $olang . "&tl=" . $tlang . "&dt=t&q=" . urlencode($text);
		$jsContents = $this->sendAPI_GET($url);
		if($jsContents){
			if (isset($jsContents[0])) {
				$result = "";
				foreach ($jsContents[0] as $key => $value) {
					$result .= $value[0];
				}
				return $result;
			}
		}
		return null;
	}

	private function detectLanguageGoogle_000host($text, $root_url) {		
		$olang = 'auto';
		$tlang = 'en';
		$url = $root_url."?fromLang=" . $olang . "&toLang=" . $tlang . "&text=" . urlencode($text);
		$jsContents = $this->sendAPI_GET($url);
		if($jsContents){
			if (isset($jsContents->fromlang)) {
				return $jsContents->fromlang;
			}
		}
		return null;
	}

	private function translateGoogle_000host($olang, $tlang, $text, $root_url) {
		$url = $root_url."?fromLang=" . $olang . "&toLang=" . $tlang . "&text=" . urlencode($text);
		$jsContents = $this->sendAPI_GET($url);
		if($jsContents){
			if (isset($jsContents->result)) {
				return $jsContents->result;
			}
		}
		return null;
	}

	private function detectLanguageYandex($text) {
		$api = 'trnsl.1.1.20190627T085838Z.85e3f7d42d59f521.d06f2f890e80f6e860a5171bda1bbbe9212d44bb';
		$url = 'https://translate.yandex.net/api/v1.5/tr.json/detect?key=' . $api . '&text=' . urlencode($text);
		$client = new Client();
		$response = $client->request('GET', $url, ['headers' => ['Content-Type' => 'application/x-www-form-urlencoded', ], 'http_errors' => false]);
		if (isset($response)) {
			$contents = $response->getBody()
				->getContents();
			$jsContents = json_decode($contents);
			if ($jsContents->code == 200) {
				return $jsContents->lang;
			}
		}
		return null;
	}

	private function translateYandex($olang, $tlang, $text) {
		$api = 'trnsl.1.1.20190627T085838Z.85e3f7d42d59f521.d06f2f890e80f6e860a5171bda1bbbe9212d44bb';
		$url = 'https://translate.yandex.net/api/v1.5/tr.json/translate?key=' . $api . '&lang=' . $olang . '-' . $tlang . '&text=' . urlencode($text);
		$client = new Client();
		$response = $client->request('GET', $url, ['headers' => ['Content-Type' => 'application/x-www-form-urlencoded', ], 'http_errors' => false]);
		if (isset($response)) {
			$contents = $response->getBody()
				->getContents();
			$jsContents = json_decode($contents);
			if ($jsContents->code == 200) {
				return $jsContents->text[0];
			}
		}
		return null;
	}

	private function sendAPI_GET($url){
        $client = new Client();
        $jsContents = null;
        try {    
            $response = $client->request("GET", $url, [
                'headers' =>['Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8'], 
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
}
