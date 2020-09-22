<?php

namespace App\WordsExtract;

class Extractor
{
    /**
     * 停止符 ｜ 分隔符.
     */
    private $stopWords;

    /**
     * 读取文本管理类.
     */
    private $contentManager;

    /**
     * 每次解析完保留的字符数.
     */
    private $preserve_num;

    /**
     * 常用词数组.
     *
     * 以常用词为键，出现次数为值
     */
    private $words;

    public function __construct(int $preserve_num = 100)
    {
        $this->stopWords = config('stop-word', []);
        $this->contentManager = ContentManager::getInstance();
        $this->preserve_num = $preserve_num;
        $this->words = [];
    }

    /**
     * @param string text 要解析的字符串，可以是文件路径
     */
    public function run(string $text, $preserve_num = null)
    {
        $preserve_num = $preserve_num ?? $this->preserve_num;

        // 读取文本
        $content = $this->contentManager->read($text);
        // 解析文本
        $words = $this->parseContent($content);
        // 返回前 100 个词
        return array_slice($words, 0, $preserve_num);
    }

    /**
     * 解析文本.
     *
     * @param string content 文本字符串
     */
    protected function parseContent($content)
    {
        $content = $this->filterUselessChar($content);
        dd($content);
    }

    /**
     * 过滤无用字符.
     *
     * @param string content 文本字符串
     */
    protected function filterUselessChar($content)
    {
        $content = preg_replace('/[a-z0-9\n\s]*/ius', '', strip_tags($content));

        return str_replace($this->stopWords, '', $content);
    }
}
