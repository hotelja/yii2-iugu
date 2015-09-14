<?php
namespace hotelja\iugu\services;

class Accounts extends \hotelja\iugu\Service
{
    public $data;
    public $files;
    public $automatic_validation=true;
    public function rules()
    {
        return [
            [['data','files'],'required','on'=>'request_verification'],
            [['automatic_validation'],'boolean'],
            [['automatic_validation'],'default','value'=>true],
        ];
    }

    public function requestVerification($id)
    {
        return $this->post($id.'/request_verification');
    }

    public static function getById($id)
    {
        return $this->get($id);
    }
}
