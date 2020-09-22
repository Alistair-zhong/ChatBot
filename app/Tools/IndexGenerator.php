<?php

namespace App\Tools;

class IndexGenerator
{
    const ALL = 1;
    const TWOLANGUAGE = 2;
    const THREELANGUAGE = 3;

    private $master;
    private $other = [
        'en_us',
        'ja_jp',
        'ko_kr',
        'es_es',
        'fr_fr',
        'it_it',
        'de_de',
        'ru_ru',
        'tl_ph',
        'hi_in',
        'sw_ke',
        'mn_mn',
        'zu_za',
        'he_il',
        'ar_sa',
        'my_mm',
        'id_id',
        'pt_pt',
        'pl_pl',
        'ro_ro',
        'el_gr',
        'nl_nl',
        'sv_se',
        'lo_la',
        'vi_vn',
        'th_th',
    ];

    public function __construct(string $master = 'zh_cn', array $other = [])
    {
        $this->master = $master;

        if (isset($other[1])) {
            $this->other = $other;
        }
    }

    public function getTwoLangIndex()
    {
        $indexes = [];

        foreach ($this->other as $other) {
            $indexes[] = $this->splice2Index($other);
        }

        return $indexes;
    }

    public function splice2Index($other)
    {
        return $this->getPrefix().$other;
    }

    public function getThreeLangIndex()
    {
        $indexes = [];
        $the_other = $this->other;
        $middle = array_shift($the_other);

        foreach ($the_other as $the_other) {
            $indexes[] = $this->splice3Index($middle, $the_other);
        }

        return $indexes;
    }

    public function splice3Index($other, $the_other)
    {
        return $this->splice2Index($other).'__'.$the_other;
    }

    public function getPrefix()
    {
        return 'mlc_post_paragraphs_union_'.$this->master.'__';
    }

    /**
     * 获取 ES 请求
     *
     * @param int option 标志变量  表明是获取哪种类型的索引请求，2 代表两种，3代表三语，1，代表全部
     */
    public function getESRequest(int $option = self::TWOLANGUAGE)
    {
        switch ($option) {
            case self::ALL:
                $indexes = $this->getAllIndex();
            break;
            case self::TWOLANGUAGE:
                $indexes = $this->getTwoLangIndex();
            break;
            case self::THREELANGUAGE:
                $indexes = $this->getThreeLangIndex();
            break;
        }

        return $this->getBulk($indexes);
    }

    /**
     * 获取 ES 请求
     *
     * @param int option 标志变量  表明是获取哪种类型的索引请求，2 代表两种，3代表三语，1，代表全部
     */
    public function getUpdateAliasParam(int $option = self::TWOLANGUAGE)
    {
        switch ($option) {
            case self::ALL:
                $indexes = $this->getAllIndex();
            break;
            case self::TWOLANGUAGE:
                $indexes = $this->getTwoLangIndex();
            break;
            case self::THREELANGUAGE:
                $indexes = $this->getThreeLangIndex();
            break;
        }

        return $this->getActionsArr($indexes);
    }

    public function getAllIndex()
    {
        $indexes = $this->getTwoLangIndex();

        return array_merge($indexes, $this->getThreeLangIndex());
    }

    public function wrapESRequest(array $indexes = [])
    {
        foreach ($indexes as $key => $index) {
            $indexes[$key] = $this->wrapRequest($index);
        }

        return $indexes;
    }

    public function wrapRequest(string $index)
    {
        return 'PUT /mlc_post_paragraphs_zh_cn/_alias/'.$index;
    }

    public function getBulk(array $indexes = [])
    {
        return 'POST /_aliases{"actions": ['.
            $this->getActions($indexes).
            ']}';
    }

    public function getActions(array $indexes = [])
    {
        $actions = '';

        foreach ($indexes as $index) {
            $actions .= '{ "add": { "index":"mlc_post_paragraphs_'.$this->master.'","alias": "'.$index.'" } },';
        }

        return trim($actions, ',');
    }

    public function getActionsArr(array $indexes){
        $actions = [];

        foreach($indexes as $index){
            $actions[] = [
                'add' => [
                    'index' => "mlc_post_paragraphs_".$this->master,
                    'alias' => $index
                ]
            ];
        }

        return $actions;
    }
}
