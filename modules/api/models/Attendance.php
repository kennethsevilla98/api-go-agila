<?php

namespace app\modules\api\models;

use Yii;

/**
 * This is the model class for table "attendance".
 *
 * @property int $id
 * @property string|null $trainingID
 * @property string|null $email
 * @property string|null $name
 * @property string|null $time_in
 * @property string|null $time_out
 */
class Attendance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'attendance';
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
            [['time_in', 'time_out'], 'safe'],
            [['trainingID', 'email', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'trainingID' => 'Training ID',
            'email' => 'Email',
            'name' => 'Name',
            'time_in' => 'Time In',
            'time_out' => 'Time Out',
        ];
    }
}
