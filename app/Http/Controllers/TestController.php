<?php

namespace App\Http\Controllers;

use App\EWAS;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function updatePValue()
    {
        $ewas = EWAS::find(1);
        $p = (float)$ewas->p_value;
        $float_p = sprintf('%1.2f',$p);
        return $float_p;
    }
}
