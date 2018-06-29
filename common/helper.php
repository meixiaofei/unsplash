<?php
/**
 * $excelTitle = 'Test export';
 * $cellName   = [
 * ['id', 'Id'],
 * ['name', '姓名'],
 * ['gender', '性别'],
 * ['province', '省'],
 * ['city', '市'],
 * ['area', '区'],
 * // ...
 * ];
 * $cellData   = [
 * ['id' => '1', 'name' => '张三', 'gender' => '男', 'province' => '湖北省', 'city' => '武汉市', 'area' => '武昌区'],
 * ['id' => '2', 'name' => '李四', 'gender' => '女', 'province' => '湖南省', 'city' => '长沙市', 'area' => '岳麓区'],
 * ['id' => '3', 'name' => '王五', 'gender' => '男', 'province' => '广东省', 'city' => '汕头市', 'area' => '龙湖区'],
 * ];
 * export_excel($excelTitle, $cellName, $cellData);
 * @param $expTitle
 * @param $expCellName
 * @param $expTableData
 * @throws Exception
 */
function export_excel($expTitle, $expCellName, $expTableData)
{
    $xlsTitle = $expTitle;
    $fileName = $xlsTitle . '-' . date('YmdHis');
    $cellNum = count($expCellName);
    $dataNum = count($expTableData);

    $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $cellName = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ'];

    $objPHPExcel->getActiveSheet()->mergeCells('A1:' . $cellName[$cellNum - 1] . '1');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle . '  Export time:' . date('Y-m-d H:i:s'));
    for ($i = 0; $i < $cellNum; $i++) {
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i] . '2', $expCellName[$i][1]);
        // set cell width
        $objPHPExcel->getActiveSheet()->getColumnDimension($cellName[$i])->setWidth(18);
    }
    for ($i = 0; $i < $dataNum; $i++) {
        for ($j = 0; $j < $cellNum; $j++) {
            $objPHPExcel->getActiveSheet()->setCellValue($cellName[$j] . ($i + 3), $expTableData[$i][$expCellName[$j][0]]);
        }
    }
    ob_end_clean();
    header('pragma:public');
    header('Content-type:application/vnd.ms-excel;charset=utf-8;name="' . $xlsTitle . '.xls"');
    header("Content-Disposition:attachment;filename=$fileName.xls");

    $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xls');
    $objWriter->save('php://output');
    exit;
}

/**
 * @param $filename
 * @return array
 * @throws Exception
 */
function read_excel($filename)
{
    if (pathinfo($filename, PATHINFO_EXTENSION) == 'xlsx') {
        // Excel2007
        $objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
    } else {
        // Excel5
        $objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xls');
    }

    $objPHPExcel = $objReader->load($filename);

    $data = [];
    for ($i = 0, $sheetLength = $objPHPExcel->getSheetCount(); $i < $sheetLength; $i++) {
        $data[] = $objPHPExcel->getActiveSheet($i)->toArray();
    }

    return $data;
}

function stringArr2Arr($stringArr)
{
    return array_map('trim', explode(',', trim($stringArr, ' []')));
}

/**
 * @param        $url
 * @param array $params
 * @param string $method
 * @param array $header
 *
 * @return mixed
 */
function http($url, $params = [], $method = 'get', $header = [])
{
    $opts = [
        CURLOPT_TIMEOUT => 30,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTPHEADER => $header,
        CURLOPT_HEADER => false,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36',
    ];
    switch ($method) {
        case 'get' :
            $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
            break;
        case 'post' :
            // 判断是否传输文件
            $params = is_array($params) ? http_build_query($params) : $params;
            $opts[CURLOPT_URL] = $url;
            $opts[CURLOPT_POST] = 1;
            $opts[CURLOPT_POSTFIELDS] = $params;
            break;
        default :
            exit('不支持的请求方式！');
    }
    $ch = curl_init();
    curl_setopt_array($ch, $opts);
    $data = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    if ($error) {
        exit('请求发生错误：' . $error);
    }

    return $data;
}

/**
 * @param $str
 * @param $find
 *
 * @return bool
 */
function check_str($str, $find)
{
    if (count(explode($find, $str)) > 1) {
        return true;
    } else {
        return false;
    }
}

/**
 * @param $str
 *
 * @return false|int
 */
function is_mobile($str)
{
    return preg_match('/^1[34578]\d{9}$/', $str);
}

/**
 * @param $str
 *
 * @return false|int
 */
function is_email($str)
{
    return preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $str);
}

/**
 * 获取数组目标值
 *
 * @param array $array
 * @param        $value
 * @param string $targetKey
 * @param string|bool $targetValue
 * @param string $default
 *
 * @return string
 */
function get_target_value($array = [], $value, $targetKey = 'key', $targetValue = 'value', $default = '--')
{
    $result = $default;
    foreach ($array as &$val) {
        if (isset($val[$targetKey]) && $val[$targetKey] == $value) {
            if (is_bool($targetValue)) {
                $result = $val;
            } else {
                $result = $val[$targetValue];
            }
            break;
        }
    }
    unset($array);

    return $result;
}

/**
 * 获取数组目标值
 *
 * @param array $array
 * @param        $value
 * @param string $targetKey
 * @param string|bool $targetValue
 * @param array $default
 *
 * @return array
 */
function get_target_values($array = [], $value, $targetKey = 'key', $targetValue = 'value', $default = [])
{
    $result = $default;
    foreach ($array as &$val) {
        if (isset($val[$targetKey]) && $val[$targetKey] == $value) {
            if (is_bool($targetValue)) {
                $result[] = $val;
            } else {
                $result[] = $val[$targetValue];
            }
        }
    }
    unset($array);

    return $result;
}

/**
 * 调试打印
 *
 * @param      $data
 * @param bool $die
 */
function fei_print($data, $die = true)
{
    echo '<pre>';
    print_r($data);
    $die && die();
}
