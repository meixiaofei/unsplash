<?php
/**
 * 只保留这俩
 */
define('SUCCESS', '00000');
define('FAIL', '00001');
define('ERROR_METHOD', '00002');   //错误的请求方法
define('ERROR_PARAM', '00003');    //非法的字段参数
define('EXCEPTION', '00004');  // 异常抛出

$languages = [
    'SUCCESS' => '操作成功',
    'FAIL' => '操作失败',
    'ERROR_METHOD' => '错误的请求方法',
    'ERROR_PARAM' => '非法的字段参数',
    'EXCEPTION' => '异常抛出',
];

foreach ($languages as $languageKey => $languageVal) {
    define($languageKey . '_LANG', $languageVal);
}
