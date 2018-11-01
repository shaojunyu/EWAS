<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\EWAS;

class SearchController extends Controller
{
    //
    public function search(Request $request)
    {
        $ewas = EWAS::all()->take(10);
//        return JsonResponse::create($ewas);
        return json_encode($ewas);
    }

}
