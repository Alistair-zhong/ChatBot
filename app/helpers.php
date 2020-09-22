<?php

/**
 * 从google api返回的元数据中提取构建file对象需要的数据
 */
if(! function_exists('parseFileData')){
    function parseFileData($file){
        return [
            'cloudId'   => $file['virtual_path'],
            'filename'  => $file['path'],
            'type'      => $file['type'],
            'size'      => $file['size'],
        ];
    }
}

/**
 * 获取微秒数
 * 
 */
if(! function_exists('microtime_float')){
    function microtime_float(){
        list($usec,$sec) = explode(" ",microtime());

        return ((float)$usec + (float)$sec);
    }
}