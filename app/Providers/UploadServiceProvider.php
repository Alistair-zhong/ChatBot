<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use niro\Uploads\Facades\HandlerContainer;
use App\Handler\GoogleDriveHandler;

class UploadServiceProvider extends ServiceProvider {
    public function boot(){

    }
    
    public function register(){
        // 注册用户自定义的处理类
        // HandlerContainer::registerHandler('google',GoogleDriveHandler::class);
    }

}