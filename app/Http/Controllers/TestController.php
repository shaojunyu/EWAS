<?php

namespace App\Http\Controllers;

use App\EWAS;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function updatePValue()
    {
        foreach (EWAS::all() as $ewas){
            $gene = $ewas->Gene_name;
            $gene_array = explode(';',$gene);
            if(count($gene_array) > 1){
                echo $gene . "<br>";
                $u = array_unique($gene_array);
                echo implode(';', $u) . "<br>";
                $ewas->Gene_name = implode(';', $u);
                $ewas->update();
                //var_dump($u);
            }

        }
    }
}
