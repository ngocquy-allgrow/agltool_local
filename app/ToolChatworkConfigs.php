<?php



namespace App;



use Illuminate\Database\Eloquent\Model;

use App\Helpers\ConvertToLang;


class ToolChatworkConfigs extends Model

{

    /**

     * The attributes that are mass assignable.

     *

     * @var array

     */

    protected $fillable = [

        'user_id', 'account_id', 'account_name', 'room_id_array', 'token'

    ];

    public function getLangOfMember($room_id) {
        $listDataRoom = ToolChatworkConfigs::select('account_name', 'user_id', 'room_id_array', 'token')->get();
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
                        'user_id' => $listRoomItem['user_id'],
                        'token' => $listRoomItem['token'],
                    ]));
                }
            }
        };

        return $memberInRoom;
    }

}

