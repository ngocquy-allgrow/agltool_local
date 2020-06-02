<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ToolSlackConfigs extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'access_token', 'scope', 'user_slack_id', 'team_id', 'enterprise_id', 'team_name', 'bot_user_id', 'bot_access_token', 'setting_language'
    ];
}
