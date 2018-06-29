<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "course_province".
 *
 * @property int $id
 * @property string $regionName
 * @property int $regionCode
 * @property string $created_at
 * @property string $updated_at
 */
class CourseProvince extends \common\models\_Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'course_province';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['regionCode'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['regionName'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'regionName' => 'Region Name',
            'regionCode' => 'Region Code',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
