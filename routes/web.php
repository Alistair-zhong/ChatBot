<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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
    return view('welcome');
});

// Route::get('test1','Controller@index');


Route::get('gofm',function (){
    // return view('vendor.file-manager.ckeditor');
    return view('file-manager::tinymce');
});
Route::group(['prefix'=>'custom',],function(){
    Route::get('share',function(){
        $filename = collect(Storage::disk('google')->files())->first();
        // https://drive.google.com/uc?id=1zEUQLI8Up6dKyldbA4M4I1KmwbgFZQOX&export=media

        return Storage::disk('google')->url($filename);
    })->name('share');
});