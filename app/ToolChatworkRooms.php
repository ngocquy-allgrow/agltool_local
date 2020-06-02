<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ToolChatworkRooms extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'room_id',
        'name',
        'token',
        'last_update_time',        
        'still_live',        
    ];
}
