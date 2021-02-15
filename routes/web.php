<?php

use App\User;
use Illuminate\Http\Request;
use Minishlink\WebPush\VAPID;
use App\WordsExtract\Extractor;
use Minishlink\WebPush\WebPush;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Minishlink\WebPush\Subscription;
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

Route::get('paginate', function () {
    return User::paginate();
});
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
        $str = str_replace(
            ['/', '.', ':', '?', ' ', '-', '+', '*', '(', ')', '[', ']', '{', '}', '^', '$', '"', "'"],
            ['\\\\', '\/', '\.', '\:', '\?', '\ ', '\-', '\+', '\*', '\(', '\)', '\[', '\]', '\{', '\}', '\^', '\$', '\"', "\'"],
            $str
        );
    }

    $end_signle = microtime_float();
    // 获取毫秒数
    $duration = ($end_signle - $start_single) * 1000;
    dd($duration);
});

// [
//     "publicKey" => "BB1En1xaOfKYHrp7TmnEPd5tCO8-JcNIBhFyPFd_C0gJkir54sK5iuMdkPoM6iUKeb6u3PiEpefxtxmPGl8rHr0",
//     "privateKey" => "-RYI8VWE0p3sAa4BZjSx27nZKzopscvS1t7lWCm--PQ",
//   ]
Route::post('postman', function (Request $request) {
    dd($request->all());
});

Route::post('webhook', function (Request $request) {
    $inputs = $request->all();
    Log::info(json_encode($inputs));
    if ($inputs['object'] !== 'page') {
        return response('object error', 404);
    }

    foreach ($inputs['entry'] as $key => $value) {
        Log::info($value['messaging'][0]);
    }
    return response('good');
});

Route::get('webhook', function (Request $request) {
    $verify_token = 'webhook';

    $inputs = $request->all();
    $mode = $inputs['hub_mode'];
    $token = $inputs['hub_verify_token'];
    $challenge = $inputs['hub_challenge'];

    if ($mode && $token) {
        if ($mode === 'subscribe' && $token === $verify_token) {
            Log::info('WEBHOOK_VERIFIED');
            return response($challenge);
        }
    }
    return response('not verified', 403);
});
