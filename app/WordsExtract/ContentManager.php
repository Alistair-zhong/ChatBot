<?php

namespace App\WordsExtract;

class ContentManager
{
    private static $instance;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * 读取文本内容 可以是文件路径.
     *
     * @param string text
     */
    public function read(string $text)
    {
        if (is_file($text)) {
            return file_get_contents($text);
        }

        return $text;
    }
}
