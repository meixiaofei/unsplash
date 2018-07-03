<?php


namespace common\controllers;


use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class TopController extends Controller
{
    public function behaviors()
    {
        return ArrayHelper::merge([
            [
                'class' => Cors::className(),
                'cors'  => [
                    'Origin'                           => ['*'],
                    'Access-Control-Request-Method'    => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                    'Access-Control-Request-Headers'   => ['*'],
                    'Access-Control-Allow-Origin'      => ['*'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age'           => 86400,
                    'Access-Control-Expose-Headers'    => [],
                ],
            ],
        ], parent::behaviors());
    }
    
    /**
     * 通用json输出 兼容原有形式
     *
     * @param mixed|string $status
     * @param string       $msg
     * @param array        $data
     *
     * @return void|\yii\web\Response
     */
    public function asJson($status = SUCCESS, $msg = '', $data = [])
    {
        if (is_array($status)) {
            parent::asJson($status);
        } else {
            if (empty($msg)) {
                $userDefined  = get_defined_constants(true)['user'];
                $flipDefined  = @array_flip($userDefined);
                $constantName = $flipDefined[$status] ?? 'NOT_EXIST';
                if ($definedLang = constant($constantName . '_LANG')) {
                    $msg = $definedLang;
                }
            }
            parent::asJson(['status' => $status, 'msg' => $msg, 'data' => $data]);
        }
    }
    
    /**
     * @return \yii\console\Application|\yii\web\Application
     */
    public function app()
    {
        return \Yii::$app;
    }
    
    /**
     * @return \yii\console\Request|\yii\web\Request
     */
    public function request()
    {
        return $this->app()->request;
    }
    
    /**
     * @return bool|mixed
     */
    public function isPost()
    {
        return $this->request()->isPost;
    }
    
    /**
     * @return bool|mixed
     */
    public function isGet()
    {
        return $this->request()->isPost;
    }
    
    /**
     * @return bool|mixed
     */
    public function isAjax()
    {
        return $this->request()->isAjax;
    }
    
    /**
     * @param null $name
     * @param null $defaultValue
     *
     * @return array|mixed
     */
    public function get($name = null, $defaultValue = null)
    {
        return $this->request()->get($name, $defaultValue);
    }
    
    /**
     * @param null $name
     * @param null $defaultValue
     *
     * @return array|mixed
     */
    public function post($name = null, $defaultValue = null)
    {
        return $this->request()->post($name, $defaultValue);
    }
    
    /**
     * @return \Exception|null
     */
    public function exception()
    {
        return $this->app()->getErrorHandler()->exception;
    }
    
    /**
     * @return \yii\caching\Cache|\yii\caching\CacheInterface
     */
    public function cache()
    {
        return $this->app()->cache;
    }
    
    /**
     * @return \yii\redis\Connection
     */
    public function redis()
    {
        return $this->app()->redis;
    }
    
    /**
     * @return \yii\db\Connection
     */
    public function db()
    {
        return $this->app()->db;
    }
}
