<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EWAS extends Model
{
    //
    protected $table = 'ewas';
    protected $hidden = ['p_value','original_p_value_string','id','beta_value','q_value'];
    public $timestamps = false;
}
