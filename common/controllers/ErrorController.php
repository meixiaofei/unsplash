<?php


namespace common\controllers;


use Yii;
use yii\web\Response;

class ErrorController extends TopController
{
    public function init()
    {
        $exception = $this->exception();
        return $this->asJson(EXCEPTION, "{$exception->getMessage()}|{$exception->getFile()}|{$exception->getLine()}|");
    }
    
    public function asJson($status = SUCCESS, $msg = '', $data = [])
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->data   = ['status' => $status, 'msg' => $msg, 'data' => $data];
        Yii::$app->end();
    }
}
