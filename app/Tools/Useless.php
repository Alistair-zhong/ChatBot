<?php

namespace App\Tools;

class Useless
{
    private $sql;
    private $tables = '';
    private $search = '';
    private $delimiter = '';
    private $lang;

    public function __construct(string $sql, string $tables = '', string $lang = '', string $search = '###', string $delimiter = ',')
    {
        $this->sql = $sql;
        $this->tables = $tables;
        $this->search = $search;
        $this->delimiter = $delimiter;
        $this->lang = $lang === '' ? $this->findLang() : $lang;
    }

    /**
     * 在输入的 sql 中查找语言代号
     */
    public function findLang()
    {
        $start = strpos($this->sql, 'paragraph_');
        $end = strpos($this->sql, '_index');
        $len = strlen('paragraph_');
        return substr($this->sql, $start + $len, $end - $start - $len);
    }

    public function getSql()
    {
        $table_names = explode($this->delimiter, $this->tables);
        $sql = $replaced = '';

        foreach ($table_names as $name) {
            $replaced = str_replace($this->search, $name, $this->sql);
            $sql .= $replaced . "\r\n";
        }

        return $sql;
    }

    /**
     * 增加语言时还需要在 wp_site_term_mapping 中增加两个字段
     */
    public function getMappingSql()
    {
        $template = "\r\n alter table `wp_site_term_mapping` add column(`term_slug_###` varchar(200) not null , `term_order_###` smallint(6)) \r\n";

        return str_replace($this->search, $this->lang, $template);
    }

    public function getSqlFile(string $file)
    {
        $sql = $this->getSql() . $this->getMappingSql();


        \file_put_contents($file, $sql);
    }
}
