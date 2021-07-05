<?php

namespace app\modules\api\models;

use Yii;

/**
 * This is the model class for table "trainingsandseminars".
 *
 * @property string|null $u_id
 * @property string $title
 * @property string $description
 * @property string|null $datesched
 */
class Trainingsandseminars extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trainingsandseminars';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('itdidb_cportal');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['url', 'title'], 'string', 'max' => 200],
            [['datesched'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'url' => 'Url',
            'title' => 'Title',
            'description' => 'Description',
            'datesched' => 'Datesched',
        ];
    }
}
