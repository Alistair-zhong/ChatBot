<?php

namespace App\Tools;

class GroupGenerator
{
    private $groups = [];
    private $members = [];
    private $days = [];

    public function __construct(array $members)
    {
        $this->members = $members;
    }

    public function run()
    {
        // 分析每个人的可用时间 根据时间段进行分组
        $this->parse($this->members);
        // 根据具体条件过滤  如 职责区分
        $this->filter($this->members);
    }

    public function parse()
    {
        foreach ($this->members as $key => $freeTime) {
            $this->groupByDay($key, $freeTime);
        }
    }

    public function groupByDay($name, $days)
    {
        foreach ($days as $day) {
            // 转换成数字 周一 => 0 周二 => 1
            $day = $this->formatDay($day);
            $this->days[$day][] = $name;
        }
    }

    public function formatDay($day)
    {
        switch ($day) {
            case '周一':
            case '一':
            case '1':
                return 0;

            case '周二':
            case '二':
            case '2':
                return 1;

            case '周三':
            case '三':
            case '3':
                return 2;

            case '周四':
            case '四':
            case '4':
                return 3;

            case '周五':
            case '五':
            case '5':
                return 4;

            case '周六':
            case '六':
            case '6':
                return 5;

            case '周日':
            case '日':
            case '7':
                return 6;
        }
    }
}
