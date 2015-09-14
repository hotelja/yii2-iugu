<?php

namespace hotelja\iugu;

use iugu\iugu\Iugu;
/**
 * This is just an example.
 */
class Iugu extends \yii\base\Component
{
    public $url='https://api.iugu.com/v1/';
    public $token;
    public $timeout=10;
    private $_httpCode;
    /**
     * @return stdClass
     */
    public function get($service,$params=[])
    {
        return $this->request($service,$params,'DELETE');
    }

    /**
     * @return stdClass
     */
    public function post($service,$params=[])
    {
        return $this->request($service,$params,'DELETE');
    }

    /**
     * @return stdClass
     */
    public function put($service,$params=[])
    {
        return $this->request($service,$params,'DELETE');
    }

    /**
     * @return stdClass
     */
    public function delete($service,$params=[])
    {
        return $this->request($service,$params,'DELETE');
    }

    /**
     * @return stdClass
     */
    public function request($service,$params,$type)
    {
        if(empty($this->token) || empty($this->url))
            throw new Exception('Invalid "token" or "url"');
        $url=rtrim($this->url,'/').'/'.$service;
        $type=strtoupper($type);
        $ch=curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout)
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if($type==='GET')
        {
            curl_setopt($ch, CURLOPT_URL, $url.'?'.http_build_query($params));
        }
        else
        {
            if($type==='POST')
                curl_setopt($ch, CURLOPT_POST, 1);
            else
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type:application/json',
            'Authorization: Basic '.$this->token,
        ]);
        try{
            $result=curl_exec($ch);
        }catch(Exception $e){}

        $this->_httpCode=curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        try{
            $result=json_decode($result);
        }catch(Exception $e){}

        if($result)
            return (object)$result;
        return $result;
    }

    /**
     * @return int
     */
    public function getHttpCode()
    {
        return $this->_httpCode;
    }
}
