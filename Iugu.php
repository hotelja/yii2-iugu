<?php

namespace hotelja\iugu;

use iugu\iugu\Iugu;
/**
 * This is just an example.
 */
class Iugu extends \yii\base\Component
{
    public $api_token;
    public function init()
    {
        parent::init();
        if(empty($this->api_token))
            throw new Exception('Invalid "api_token"');
        Iugu::setApiKey($this->api_token);
    }
}
