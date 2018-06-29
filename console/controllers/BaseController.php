<?php


namespace console\controllers;


use Yii;
use yii\console\Controller;

class BaseController extends Controller
{
    /**
     * @param string $status
     * @param string $msg
     * @param array  $data
     *
     * @return mixed|string
     */
    public function asJson($status = SUCCESS, $msg = '', $data = [])
    {
        if (is_array($status)) {
            return json_encode($status, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }
        if (empty($msg)) {
            $userDefined  = get_defined_constants(true)['user'];
            $flipDefined  = @array_flip($userDefined);
            $constantName = $flipDefined[$status];
            if ($definedLang = constant($constantName . '_LANG')) {
                $msg = $definedLang;
            }
        }
        
        return json_encode(['status' => $status, 'msg' => $msg, 'data' => $data], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * @return \yii\console\Application|\yii\web\Application
     */
    public function app()
    {
        return Yii::$app;
    }
    
    /**
     * @return \yii\db\Connection
     */
    public function db()
    {
        return $this->app()->db;
    }
    
    /**
     * @return \yii\redis\Connection
     */
    public function redis()
    {
        return $this->app()->redis;
    }
    
    /**
     * @return \yii\caching\Cache|\yii\caching\CacheInterface
     */
    public function cache()
    {
        return $this->app()->cache;
    }
}
