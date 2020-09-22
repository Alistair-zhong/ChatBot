<?php

use App\WordsExtract\Extractor;
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

Route::get('gofm', function () {
    // return view('vendor.file-manager.ckeditor');
    return view('file-manager::tinymce');
});
Route::group(['prefix' => 'custom'], function () {
    Route::get('share', function () {
        $filename = collect(Storage::disk('google')->files())->first();
        // https://drive.google.com/uc?id=1zEUQLI8Up6dKyldbA4M4I1KmwbgFZQOX&export=media

        return Storage::disk('google')->url($filename);
    })->name('share');
});
Route::get('parse', function () {
    $worker = new Extractor();
    $worker->run('/Users/niro/Downloads/test.txt');
});

Route::get('test', function () {
    // 测试 str_replace 多次调用传递字符串参数与单次调用传递数组性能
    $range = range(1, 10000);
    $start_single = microtime_float();

    foreach ($range as $item) {
        $str = '信<mark class="mark-04 match_stemming">神</mark><mark class="mark-04 match_stemming">的</mark>路就是<mark class="mark-04 match_stemming">爱</mark><mark class="mark-04 match_stemming">神</mark><mark class="mark-04 match_stemming">的</mark>路，你信<mark class="mark-04 match_stemming">神</mark>就得<mark class="mark-04 match_stemming">爱</mark><mark class="mark-04 match_stemming">神</mark>，但<mark class="mark-04 match_stemming">爱</mark><mark class="mark-04 match_stemming">神</mark>不是单指报答<mark class="mark-04 match_stemming">神</mark><mark class="mark-04 match_stemming">的</mark><mark class="mark-04 match_stemming">爱</mark>，也不是凭良心感觉去<mark class="mark-04 match_stemming">爱</mark><mark class="mark-04 match_stemming">神</mark>，乃是单纯地<mark class="mark-04 match_stemming">爱</mark><mark class="mark-04 match_stemming">神</mark>。有时候人只凭良心并不能感觉<mark class="mark-04 match_stemming">神</mark><mark class="mark-04 match_stemming">的</mark><mark class="mark-04 match_stemming">爱</mark>，为什么以前总说“愿<mark class="mark-04 match_stemming">神</mark><mark class="mark-04 match_stemming">的</mark>灵感"';
        // $str = str_replace( '\\', '\\\\', $str );
        // $str = str_replace( '/', '\/', $str );
        // $str = str_replace( '.', '\.', $str );
        // $str = str_replace( ':', '\:', $str );
        // $str = str_replace( '?', '\?', $str );
        // $str = str_replace( ' ', '\ ', $str );
        // $str = str_replace( '-', '\-', $str );
        // $str = str_replace( '+', '\+', $str );
        // $str = str_replace( '*', '\*', $str );
        // $str = str_replace( '(', '\(', $str );
        // $str = str_replace( ')', '\)', $str );
        // $str = str_replace( '[', '\[', $str );
        // $str = str_replace( ']', '\]', $str );
        // $str = str_replace( '{', '\{', $str );
        // $str = str_replace( '}', '\}', $str );
        // $str = str_replace( '^', '\^', $str );
        // $str = str_replace( '$', '\$', $str );
        // $str = str_replace( '"', '\"', $str );
        // $str = str_replace( "'", "\'", $str );
        $str = str_replace(['/', '.', ':', '?', ' ', '-', '+', '*', '(', ')', '[', ']', '{', '}', '^', '$', '"', "'"],
                            ['\\\\', '\/', '\.', '\:', '\?', '\ ', '\-', '\+', '\*', '\(', '\)', '\[', '\]', '\{', '\}', '\^', '\$', '\"', "\'"],
                            $str
        );
    }

    $end_signle = microtime_float();
    // 获取毫秒数
    $duration = ($end_signle - $start_single) * 1000;
    dd($duration);
});

Route::post('nnn/{name}', function ($name) {
    dd($name);
});
