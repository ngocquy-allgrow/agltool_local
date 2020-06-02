<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TranslationSystemInfos extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'url',
        'char_length',
        'count',
        'error_num',
        'error_at',        
        'note',        
        'status',        
    ];
}
