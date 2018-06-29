<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "course_cate_detail".
 *
 * @property int $id
 * @property int $categoryId
 * @property int $regionCode
 * @property string $floorPrice
 * @property string $topPrice
 * @property string $categoryCode
 * @property string $detail
 * @property array $jobDirections
 * @property array $testSubjects
 * @property array $schools
 * @property array $testTimes
 * @property array $testDates
 * @property string $degree
 * @property string $education
 * @property array $eliteLecturers
 * @property string $subjectDesc
 * @property string $created_at
 * @property string $updated_at
 */
class CourseCateDetail extends \common\models\_Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'course_cate_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['categoryId'], 'required'],
            [['categoryId', 'regionCode'], 'integer'],
            [['floorPrice', 'topPrice'], 'number'],
            [['jobDirections', 'testSubjects', 'schools', 'testTimes', 'testDates', 'eliteLecturers', 'created_at', 'updated_at'], 'safe'],
            [['categoryCode', 'degree', 'education'], 'string', 'max' => 20],
            [['detail', 'subjectDesc'], 'string', 'max' => 255],
            [['categoryId', 'regionCode'], 'unique', 'targetAttribute' => ['categoryId', 'regionCode']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'categoryId' => 'Category ID',
            'regionCode' => 'Region Code',
            'floorPrice' => 'Floor Price',
            'topPrice' => 'Top Price',
            'categoryCode' => 'Category Code',
            'detail' => 'Detail',
            'jobDirections' => 'Job Directions',
            'testSubjects' => 'Test Subjects',
            'schools' => 'Schools',
            'testTimes' => 'Test Times',
            'testDates' => 'Test Dates',
            'degree' => 'Degree',
            'education' => 'Education',
            'eliteLecturers' => 'Elite Lecturers',
            'subjectDesc' => 'Subject Desc',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
