<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class _Base extends ActiveRecord
{
    /**
     * @param string $status
     * @param string $msg
     * @param array  $data
     *
     * @return array
     */
    public static function modReturn($status = SUCCESS, $msg = '', $data = [])
    {
        if (empty($msg)) {
            $userDefined  = get_defined_constants(true)['user'];
            $flipDefined  = @array_flip($userDefined);
            $constantName = $flipDefined[$status];
            if ($definedLang = constant($constantName . '_LANG')) {
                $msg = $definedLang;
            }
        }
        
        return ['status' => $status, 'msg' => $msg, 'data' => $data];
    }
    
    /**
     * @return \yii\console\Application|\yii\web\Application
     */
    public static function app()
    {
        return Yii::$app;
    }
    
    /**
     * @return mixed|\yii\web\User
     */
    public static function user()
    {
        return self::app()->user;
    }
    
    /**
     * @return \yii\caching\Cache|\yii\caching\CacheInterface
     */
    public static function cache()
    {
        return self::app()->cache;
    }
    
    /**
     * @return \yii\redis\Connection
     */
    public static function redis()
    {
        return self::app()->redis;
    }
    
    /**
     * @return \yii\db\Connection
     */
    public static function db()
    {
        return self::app()->db;
    }
    
    /**
     * @param array $dataArr
     *
     * @return int
     */
    public static function addAll($dataArr = [])
    {
        if (!empty($dataArr)) {
            $fields = array_keys($dataArr[0]);
            $values = [];
            foreach ($dataArr as $dataKey => $dataVal) {
                $values[] = array_values($dataVal);
            }
            
            // (自动sample)当有异常发生时会自动捕获全局异常 输出"标准输出"
            return self::db()->createCommand()->batchInsert(self::tableName(), $fields, $values)->execute();
        }
    }
    
    /**
     * 目前只处理页码 分页大小
     * 1.将完全匹配'true' or 'false'的值转化成 boolean
     * 2.当值为空时 unset此字段
     *
     * @param array $param
     *
     * @return array
     */
    public static function prepareParam($param = [])
    {
        foreach (array_keys($param) as $keyName) {
            if (is_string($param[$keyName])) {
                $trimValue = trim($param[$keyName]);
                switch ($trimValue) {
                    case 'true':
                        $trimValue = true;
                        break;
                    case 'false':
                        $trimValue = false;
                        break;
                    case 'null':
                        $trimValue = null;
                        break;
                }
                $param[$keyName] = $trimValue;
                if ($trimValue === '') {
                    unset($param[$keyName]);
                }
            }
        }
        
        $maxLimit        = 200;
        $limit           = $param['page_size'] ?? 20;
        $param['page']   = $param['page'] ?? 1;
        $param['limit']  = $limit < $maxLimit ? $limit : $maxLimit;
        $param['offset'] = $param['limit'] * ($param['page'] - 1);
        
        return $param;
    }
    
    /**
     * @param     $timeString
     * 自动补成全时间格式 即 年-月-日 时:分:秒
     *
     * @return array
     * @throws \Exception
     */
    public static function explodeTimeRange($timeString)
    {
        $tmp = array_map('trim', explode(' - ', $timeString));
        if (count($tmp) != 2) {
            throw new \Exception('输入日期区间格式不对,应用 - 这样的东西分隔');
        }
        if (false !== strpos($timeString, ':')) {
            $range = $tmp;
        } else {
            $range = [$tmp[0] . ' 00:00:00', $tmp[1] . ' 23:59:59'];
        }
        
        return $range;
    }
    
    /**
     * @param array $update
     * @param array $where
     * @param array $params
     *
     * @return array|int
     * @throws \Exception
     */
    public static function updateAll($update = [], $where = [], $params = [])
    {
        if (empty($update)) {
            throw new \Exception('你还没说要更新啥东东呢~', [], FAIL);
        }
        
        parent::updateAll($update, $where, $params);
        
        return self::modReturn();
    }
}
