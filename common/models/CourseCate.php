<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "course_cate".
 *
 * @property int $id
 * @property string $name
 * @property string $imageUrl
 * @property int $imageUrlClicked
 * @property int $isShowPrice
 * @property string $floorPrice
 * @property string $topPrice
 * @property int $hotOrder
 * @property string $created_at
 * @property string $updated_at
 */
class CourseCate extends \common\models\_BaseCate
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'course_cate';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['imageUrlClicked', 'isShowPrice', 'hotOrder'], 'integer'],
            [['floorPrice', 'topPrice'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 20],
            [['imageUrl'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'imageUrl' => 'Image Url',
            'imageUrlClicked' => 'Image Url Clicked',
            'isShowPrice' => 'Is Show Price',
            'floorPrice' => 'Floor Price',
            'topPrice' => 'Top Price',
            'hotOrder' => 'Hot Order',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
