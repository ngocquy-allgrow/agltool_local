<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ToolSlackEvents extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_ts',
        'token',
        'team_id',
        'api_app_id',
        'event',
        'type',
        'event_id',
        'event_time',
        'authed_users',
        'ts_bot',
        'message',
    ];
}
