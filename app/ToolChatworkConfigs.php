<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
