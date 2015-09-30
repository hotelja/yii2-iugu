<?php
namespace hotelja\iugu;
/**
 * This is just an example.
 */
class Iugu extends \yii\base\Component
{
    const MODE_TEST='TEST';

    public $url='https://api.iugu.com/v1/';
    public $token;
    public $timeout=10;
    private $_httpCode;
    /**
     * @return stdClass
     */
    public function get($service,$params=[])
    {
        return $this->request('GET',$service,$params);
    }

    /**
     * @return stdClass
     */
    public function post($service,$params=[])
    {
        return $this->request('POST',$service,$params);
    }

    /**
     * @return stdClass
     */
    public function put($service,$params=[])
    {
        return $this->request('PUT',$service,$params);
    }

    /**
     * @return stdClass
     */
    public function delete($service,$params=[])
    {
        return $this->request('DELETE',$service,$params);
    }

    /**
     * @param string $type request type, 'GET', 'POST', 'PUT' or 'DELETE'
     * @param string $service service name, ex: 'accounts'
     * @param array $params params to send, ex: ['name'=>'test']
     * @return stdClass
     */
    public function request($type,$service,$params=[])
    {
        if(empty($this->token) || empty($this->url))
            throw new Exception('Invalid "token" or "url"');
        $url=rtrim($this->url,'/').'/'.$service;
        $type=strtoupper($type);
        $ch=curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if($type==='GET')
        {
            curl_setopt($ch, CURLOPT_URL, $url.($params!==[] ? '?'.http_build_query($params) : ''));
        }
        else
        {
            if($type==='POST')
                curl_setopt($ch, CURLOPT_POST, true);
            else
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params,'','&'));
        }

        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $this->token.":");
        if(YII_DEBUG)
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        try{
            $response=curl_exec($ch);
        }catch(Exception $e){}

        if(curl_errno($ch)!==0)
            return false;

        $info=curl_getinfo($ch);
        $this->_httpCode=$info['http_code'];
        curl_close($ch);
        if(strpos($info['content_type'],'application/json')===0)
        {
            $response=json_decode($response);
            if(YII_DEBUG)
            {
                \Yii::info($this->_httpCode.':'.PHP_EOL.
                    print_r($info,true).PHP_EOL.
                    print_r($response,true).PHP_EOL
                ,'iugu');
            }
            return (object)$response;
        }
        if(YII_DEBUG)
        {
            \Yii::info($this->_httpCode.':'.PHP_EOL.
                print_r($info,true).PHP_EOL.
                $response.PHP_EOL
            ,'iugu');
        }
        return false;
    }

    /**
     * @return int
     */
    public function getHttpCode()
    {
        return $this->_httpCode;
    }
}
