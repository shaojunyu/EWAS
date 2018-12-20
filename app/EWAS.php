<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EWAS extends Model
{
    //
    protected $table = 'ewas';
    protected $hidden = ['p_value','original_p_value_string'];
    public $timestamps = false;
}
