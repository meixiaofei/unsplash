<?php


namespace console\controllers;


use common\models\CourseCate;
use common\models\CourseCateDetail;
use common\models\CourseGoods;
use common\models\CourseProvince;

class CourseController extends BaseController
{
    static $remoteProvince   = 'https://cc-skynet.ministudy.com/api/shopping/base/listAllProvinces';
    static $remoteCate       = 'https://cc-skynet.ministudy.com/api/shopping/base/listChildren';
    static $remoteCateDetail = 'https://cc-skynet.ministudy.com/api/shopping/category/getCategoryDetail';
    static $remoteGoodsList  = 'https://cc-skynet.ministudy.com/api/shopping/goods/listGoods';
    
    public function actionCollectProvince()
    {
        $remoteResult = json_decode($this->http(self::$remoteProvince), true);
        CourseProvince::addAll($remoteResult['data']);
    }
    
    /**
     * php yii course/collect-or-update-cate
     */
    public function actionCollectOrUpdateCate()
    {
        $a = [
            "id"              => 67,
            "name"            => "行政管理",
            "imageUrl"        => null,
            "imageUrlClicked" => null,
            "isShowPrice"     => 1,
            "floorPrice"      => "1980.00",
            "topPrice"        => "12980.00",
            "hotOrder"        => null,
        ];
        
        $courseCateData               = [];
        $coursePriceData              = [];
        $existCoursePriceCidRegionArr = CourseCateDetail::find()->select(['CONCAT(categoryId,regionCode)'])->column() ?: [];
        $existCourseCateIdArr         = CourseCate::find()->select('id')->column() ?: [];
        foreach (CourseProvince::find()->all() as $courseProvince) {
            foreach (CourseCate::find()->where(['pid' => 0])->all() as $courseCate) {
                $remoteResult = json_decode($this->http(self::$remoteCate, '{"parentId":' . $courseCate->id . ',"regionCode":"' . $courseProvince->regionCode . '"}'), true);
                foreach ($remoteResult['data'] as $data) {
                    $cidRegion = $data['id'] . $courseProvince->regionCode;
                    $priceData = ['categoryId' => $data['id'], 'regionCode' => $courseProvince->regionCode, 'floorPrice' => $data['floorPrice'], 'topPrice' => $data['topPrice']];
                    if (!in_array($cidRegion, $existCoursePriceCidRegionArr)) {
                        array_push($existCoursePriceCidRegionArr, $cidRegion);
                        $coursePriceData[] = $priceData;
                    } else {
                        CourseCateDetail::updateAll($priceData, ['categoryId' => $data['id'], 'regionCode' => $courseProvince->regionCode]);
                    }
                    
                    unset($data['floorPrice']);
                    unset($data['topPrice']);
                    $data['pid'] = $courseCate->id;
                    if (!in_array($data['id'], $existCourseCateIdArr)) {
                        array_push($existCourseCateIdArr, $data['id']);
                        $courseCateData[] = $data;
                    } else {
                        CourseCate::updateAll($data, ['id' => $data['id']]);
                    }
                }
            }
        }
        
        CourseCate::addAll($courseCateData);
        CourseCateDetail::addAll($coursePriceData);
    }
    
    /**
     * php yii course/update-cate-detail
     */
    public function actionUpdateCateDetail()
    {
        foreach (CourseCateDetail::find()->all() as $courseCateDetail) {
            $remoteResult = json_decode($this->http(self::$remoteCateDetail, '{"categoryId":' . $courseCateDetail->categoryId . ',"regionCode":"' . $courseCateDetail->regionCode . '"}'), true);
            $courseCateDetail->setAttributes($remoteResult['data']);
            $courseCateDetail->save(false);
        }
    }
    
    /**
     * php yii course/collect-goods
     */
    public function actionCollectGoods($processNum = 10)
    {
        $totalCourseCateDetail = CourseCateDetail::find()->count('id');
        $every                 = ceil($totalCourseCateDetail / $processNum);
        
        $remoteResult = json_decode($this->http(self::$remoteGoodsList, '{"categoryId":' . $courseCateDetail->categoryId . ',"regionCode":"' . $courseCateDetail->regionCode . '"}'), true);
        if (!$remoteResult['data']) {
            die('token expired');
        }
        $this->app()->db->createCommand()->truncateTable(CourseGoods::tableName())->execute();
        $this->app()->db->close();
        
        for ($i = 0; $i < $processNum; $i++) {
            $pid = pcntl_fork();
            if ($pid == -1) { //进程创建失败
                die('fork child process failure!');
            } elseif ($pid) { //父进程处理逻辑
                pcntl_wait($status, WNOHANG);
            } else { //子进程处理逻辑
                $this->app()->db->open();
                $this->processCollectGoods(['between', 'id', $every * $i, $every * ($i + 1) - 1]);
            }
        }
    }
    
    private function processCollectGoods($where)
    {
        foreach (CourseCateDetail::find()->where($where)->all() as $courseCateDetail) {
            $remoteResult = json_decode($this->http(self::$remoteGoodsList, '{"categoryId":' . $courseCateDetail->categoryId . ',"regionCode":"' . $courseCateDetail->regionCode . '"}'), true);
            /*
             * {
	"flag": 1,
	"message": "",
	"data": [{
		"id": 1464,
		"price": 4800.00,
		"name": "【HL】口碑精讲班【全科】",
		"studentNum": 862,
		"thirdSystemId": 1824201
	}, {
		"id": 1494,
		"price": 6800.00,
		"name": "【HL】零基础双师班【全科】",
		"studentNum": 1554,
		"thirdSystemId": 1824203
	}, {
		"id": 2656,
		"price": 9800.00,
		"name": "2018年注会高级实验班",
		"studentNum": 1484,
		"thirdSystemId": 1824620
	}, {
		"id": 2686,
		"price": 13800.00,
		"name": "2018年VIP订制无忧班",
		"studentNum": 130,
		"thirdSystemId": 1824621
	}, {
		"id": 642,
		"price": 15800.00,
		"name": "2018年事务所精品实战班",
		"studentNum": 112,
		"thirdSystemId": 1824105
	}]
}
            */
            foreach ($remoteResult['data'] as $key => &$data) {
                $data['categoryId'] = $courseCateDetail->categoryId;
                $data['regionCode'] = $courseCateDetail->regionCode;
            }
            CourseGoods::addAll($remoteResult['data']);
        }
        die;
    }
    
    private function http($url, $params = [], $method = 'post')
    {
        return http($url, $params, $method, ['token: eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIyMTIiLCJleHAiOjE1MzAyNjI3NzcsImlhdCI6MTUzMDE3NjM3NywianRpIjoiMCJ9.MbCvvV4wJDZW2FlhxmjusbHiynT67FmrAFyAqx_TomA', 'Content-Type: application/json;charset=UTF-8']);
    }
}
