<?php
namespace hotelja\iugu\services;

class Marketplace extends \hotelja\iugu\Form
{
    public $name;
    public $commission_percent;
    public function rules()
    {
        return [
            [['name','commission_percent'],'required','on'=>'create_account',],
            [['commission_percent'],'double',],
            ['automatic_transfer','boolean'],
        ];
    }
}
