<?php



namespace App\Http\Controllers\Tool\ChatWorkTranslateV3;



use GuzzleHttp\Client;

use Illuminate\Http\Request;

use Illuminate\Http\Response;

use Illuminate\Routing\Controller as BaseController;



use App\Http\Controllers\Tool\TranslationCore\TranslationCoreController;

use App\Http\Controllers\Tool\ChatWorkTranslateV3\ChatWorkCoreController;


use App\Helpers\ConvertToLang;



use App\ToolChatworkConfigs;

use App\ToolChatworkMessages;

use App\ToolChatworkRooms;


use App\User;

use App\Role;



class ChatWorkAdminController extends BaseController

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

        $request->user()->authorizeRoles(['admin']);



        $chatworkRooms = ToolChatworkRooms::orderBy('last_update_time', 'DESC')->get();



        $chatworkRoom = ToolChatworkRooms::orderBy('last_update_time', 'DESC')->first();

        $messages = (new ChatWorkCoreController)->get_full_room_info($chatworkRoom->token, $chatworkRoom->room_id);



        $datas = $this->show_message($messages);



        return view('tool/chatwork_translate_v3/admin',[

            'chatworkRooms'           => $chatworkRooms,

            'room_id' => $chatworkRoom->room_id,

            'datas' => $datas

        ]);

    }



    public function detail(Request $request)

    {

        $request->user()->authorizeRoles(['admin']);

        

    	$room_id = $request->route('room_id');



        $chatworkRooms = ToolChatworkRooms::orderBy('last_update_time', 'DESC')->get();



        foreach ($chatworkRooms as $chatworkRoom) {

        	if($room_id ==  $chatworkRoom->room_id){

        		$messages = (new ChatWorkCoreController)->get_full_room_info($chatworkRoom->token, $chatworkRoom->room_id);

        	}

        }

        
        $datas = $this->show_message($messages);

        $listDataRoom = ToolChatworkConfigs::select('account_name', 'room_id_array')->get();
        $memberInRoom = [];

        foreach($listDataRoom as $listRoomItem)
        {
            foreach(json_decode($listRoomItem['room_id_array']) as $room)
            {
                if ($room_id == $room->room_id)
                {
                    $convertLang = (new ConvertToLang)->convertToLang($room->lang);

                    array_push($memberInRoom, json_encode([
                        'username'=> $listRoomItem['account_name'],
                        'room_id' => $room->room_id, 'lang'=> $convertLang,
                        'key_lang' => $room->lang,
                    ]));
                }
            }
        };
       
        return view('tool/chatwork_translate_v3/admin',[

            'chatworkRooms'      => $chatworkRooms,

            'room_id' => $room_id,

            'datas' => $datas,

            'members' => $memberInRoom

        ]);

    }



    private function show_message($messages){

    	$chatworkAccounts = ToolChatworkConfigs::all();

    	if(!$messages) $messages = [];

    	foreach ($messages as $message) {

    		foreach ($chatworkAccounts as $chatworkAccount) {

    			if($message->account->account_id == $chatworkAccount->account_id){

    				$message->token = $chatworkAccount->token;

    			}

    		}

    	}



    	foreach ($messages as $k_message => $message) {

    		if(!isset($message->token) || trim($message->body) == "[deleted]"){

                unset($messages[$k_message]);

    		}

    	}



    	usort($messages, function($a, $b) {return strcmp($b->message_id, $a->message_id);});



    	return $messages;

    }



    public function editMessage(Request $request){

    	$token = $request->input('token');

    	$room_id = $request->input('room_id');

    	$message_id = $request->input('message_id');

    	$body = $request->input('body');

    	$response = (new ChatWorkCoreController)->update_message($token, $room_id, $message_id, $body);
  
        if(isset($response->message_id)){

            $result = (new ChatWorkCoreController)->get_message_info($token, $room_id, $response->message_id);

            if($result){



            	$whereData = [

	                'room_id' => $room_id,

	                'message_id' => $message_id,

	            ];

	            $toolChatworkMessages = ToolChatworkMessages::firstOrNew($whereData);

                $toolChatworkMessages->body = $result->body;

                $toolChatworkMessages->send_time = $result->send_time;

                $toolChatworkMessages->update_time = $result->update_time;

                $toolChatworkMessages->save();



                $whereData = [

	                'room_id' => $room_id,

	            ];

	            $toolChatworkRoom = ToolChatworkRooms::where($whereData)->first();

                $toolChatworkRoom->last_update_time = $result->update_time;

                $toolChatworkRoom->save();

            }
      

        }


        return redirect( route('chatwork_admin_detail',['room_id' => $room_id]));

    }



    public function delMessage(Request $request){

        $token = $request->input('token');

        $room_id = $request->input('room_id');

        $message_id = $request->input('message_id');



        $response = (new ChatWorkCoreController)->delete_message($token, $room_id, $message_id);



        return redirect( route('chatwork_admin_detail',['room_id' => $room_id]));

    }



    public function deleteRoom(){

    	

		$client = new Client();



        $chatworkAccounts = ToolChatworkConfigs::all();

        foreach ($chatworkAccounts as $chatworkAccount) {

            $datas = json_decode($chatworkAccount->room_id_array, true);

            if($datas){

                foreach ($datas as $data) {                    

                    $url = 'https://api.chatwork.com/v2/rooms/';

		        	$response = $client->request('GET', $url.$data['room_id'], [

		                'headers' =>[

		                    'X-ChatWorkToken' => $chatworkAccount->token

		                ],

		                'http_errors' => false

		            ]);



		        	$contents = $response->getBody()->getContents(); 

		            $jsContents = json_decode($contents);

	                if(isset($jsContents->errors)){

	                    echo $data['room_id'];

	                    echo "--------";

	                    echo $chatworkAccount->account_name;

			            echo "--------";

			            print("<pre>".print_r($jsContents->errors,true)."</pre>");

			            echo "<br>";    

			            //$chatworkAccount->room_id_array = null;

			            //$chatworkAccount->save();           

	                }         

                }                

            }

        }      	



    }

}

