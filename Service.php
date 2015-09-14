<?php
namespace hotelja\iugu;

use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\web\HttpException;
abstract class Service extends \yii\base\Model
{
    /**
     * Returns the component used by this service class.
     * By default, the "db" application component is used as the component.
     * You may override this method if you want to use a different component.
     * @return Iugu the iugu component used by this service class.
     */
    public static function getIugu()
    {
        return Yii::$app->getComponent('iugu');
    }

    /**
     * Declares the name of the database service associated with this AR class.
     * By default this method returns the class name as the service name by calling [[Inflector::camel2id()]]
     * with prefix [[Connection::servicePrefix]]. For example if [[Connection::servicePrefix]] is 'tbl_',
     * 'Customer' becomes 'tbl_customer', and 'OrderItem' becomes 'tbl_order_item'. You may override this method
     * if the service is not named after this convention.
     * @return string the service name
     */
    public static function serviceName()
    {
        return Inflector::camel2id(StringHelper::basename(get_called_class()), '_');
    }

    /**
     * @return Service
     */
    public function get($method=null)
    {
        $response=static::getIugu()->request(
            $this->_getServiceName($method),
            $this->getAttributes(),
            'GET'
        );
        return $this->handleResponse($response);
    }

    /**
     * @return Service
     */
    public function post($method=null)
    {
        $response=static::getIugu()->request(
            $this->_getServiceName($method),
            $this->getAttributes(),
            'POST'
        );
        return $this->handleResponse($response);
    }

    /**
     * @return Service
     */
    public function put($method=null)
    {
        $response=static::getIugu()->request(
            $this->_getServiceName($method),
            $this->getAttributes(),
            'PUT'
        );
        return $this->handleResponse($response);
    }

    /**
     * @return Service
     */
    public function delete($method=null)
    {
        $response=static::getIugu()->request(
            $this->_getServiceName($method),
            $this->getAttributes(),
            'DELETE'
        );
        return $this->handleResponse($response);
    }

    /**
     * @return string
     */
    private function _getServiceName($method)
    {
        $serviceName=static::serviceName();
        if($method)
            $serviceName.='/'.$method;
        return $serviceName;
    }

    protected function handleResponse($response)
    {
        if(isset($response->errors))
        {
            if(is_array($response->errors))
            {
                $this->addErrors($response->errors);
            }
            else
                throw new HttpException(static::getIugu()->httpCode, $response->errors);
        }
        elseif(isset($response->success) && !$response->success)
        {
            return false;
        }
        return $response;
    }
}
