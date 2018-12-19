<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $tissue = DB::table('ewas')->select('Tissue')->distinct()->get();
    $trait = DB::table('ewas')->select('Trait')->distinct()->get();
    return view('welcome', ['tissue'=>$tissue, 'trait'=>$trait]);
});

Route::post('/search','SearchController@search');
Route::post('/download', 'SearchController@download');

Route::get('/test', 'TestController@updatePValue');