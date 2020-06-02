<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatworkAccounts extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'account_id', 'room_id_array', 'access_token', 'refreshed_access_token'
    ];
}
