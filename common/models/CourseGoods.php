<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "course_goods".
 *
 * @property int $id
 * @property int $categoryId
 * @property int $regionCode
 * @property string $price
 * @property string $name
 * @property int $studentNum
 * @property int $thirdSystemId
 * @property string $created_at
 * @property string $updated_at
 */
class CourseGoods extends \common\models\_Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'course_goods';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['categoryId', 'regionCode', 'studentNum', 'thirdSystemId'], 'integer'],
            [['price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 50],
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
            'price' => 'Price',
            'name' => 'Name',
            'studentNum' => 'Student Num',
            'thirdSystemId' => 'Third System ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
