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
        $chr = $request->input('chr', '0');
        $gene_or_position = $request->input('gene_or_position', '0');
        $gene_text = $request->input('gene_text', '');
        $pval = $request->input('pval', 0);
        $trait = $request->input('trait', '');
        $tissue = $request->input('tissue', '');
        $page = $request->input('page', 1);

        $ewas = EWAS::whereNotNull('cpg_ID');

        $order = false;
        if ($chr >= 1 and $chr <= 23) {
            $ewas = $ewas->where('chr', $chr);
            $order = true;
        }
        if ($gene_or_position === '0' and !empty($gene_text)) { // search by gene name
            $ewas = $ewas->where('Gene_name', 'like', "%$gene_text%");
            $order = true;
        }

        if ($gene_or_position === '1' and !empty($gene_text)) { // search by position
            $ewas = $ewas->where('position', $gene_text);
            $order = true;
        }

        if ($pval > 0) {
            $pval = pow(10, -(11 - $pval));
            $ewas = $ewas->where('p_value', '<=', $pval);
            $order = true;
        }

//        if(count($trait) > 0){
//            $ewas = $ewas->whereIn('Trait', $trait);
//        }
//
//        if(count($tissue) > 0){
//            $ewas = $ewas->whereIn('Tissue', $tissue);
//        }

        if (!empty($trait)) {
            $ewas = $ewas->where('Trait', $trait);
            $order = true;
        }

        if (!empty($tissue)) {
            $ewas = $ewas->where('Tissue', $tissue);
            $order = true;
        }

        if ($order) {
            $ewas = $ewas->orderBy('p_value', 'desc');
        }

        $count = $ewas->count();

        $res = $ewas->skip(($page - 1) * 10)->take(20)->get();
        return JsonResponse::create([
            'count' => $count,
            'current' => $page,
            'data' => $res
        ]);
    }

    public function download(Request $request)
    {
        $chr = $request->input('chr', '0');
        $gene_or_position = $request->input('gene_or_position', '0');
        $gene_text = $request->input('gene_text', '');
        $pval = $request->input('pval', 0);
        $trait = $request->input('trait', '');
        $tissue = $request->input('tissue', '');

        $ewas = EWAS::whereNotNull('cpg_ID');

        if ($chr >= 1 and $chr <= 23) {
            $ewas = $ewas->where('chr', $chr);
        }
        if ($gene_or_position === '0' and !empty($gene_text)) { // search by gene name
            $ewas = $ewas->where('Gene_name', 'like', "%$gene_text%");
        }

        if ($gene_or_position === '1' and !empty($gene_text)) { // search by position
            $ewas = $ewas->where('position', $gene_text);
        }

        if ($pval > 0) {
            $pval = pow(10, -(11 - $pval));
            $ewas = $ewas->where('p_value', '<=', $pval);
        }
        if (!empty($trait)) {
            $ewas = $ewas->where('Trait', $trait);
        }

        if (!empty($tissue)) {
            $ewas = $ewas->where('Tissue', $tissue);
        }
        $res = $ewas->get();

        $name = './storage/'.uniqid() .'.csv';
        $fp = fopen($name, 'w');
        fputcsv($fp, ['cpg_ID','Trait','PMID','GEO_ID','chr','position','Tissue','p_value','Gene']);
        foreach ($res as $fields) {
            fputcsv($fp, $fields->toArray());
        }

        fclose($fp);

        return $name;

    }
}
