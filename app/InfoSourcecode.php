<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InfoSourcecode extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'name', 'result','result_css', 'result_perfectpixel', 'created_at'
    ];
}
