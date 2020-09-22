<?php

namespace App\Tools;

class Useless {
    private $sql;
    private $tables = '';
    private $search = '';
    private $delimiter = '';

    public function __construct(string $sql, string $tables = '',string $search = '###', string $delimiter = ','){
        $this->sql = $sql;
        $this->tables = $tables;
        $this->search = $search;
        $this->delimiter = $delimiter;
    }

    public function getSql(){
        $table_names = explode($this->delimiter,$this->tables);
        $sql = $replaced = '';

        foreach($table_names as $name){
            $replaced = str_replace($this->search, $name, $this->sql);
            $sql .= $replaced."\r\n";
        }

        return $sql;
    }

    public function getSqlFile(string $file){
        $sql = $this->getSql();

        \file_put_contents($file,$sql);
    }
}