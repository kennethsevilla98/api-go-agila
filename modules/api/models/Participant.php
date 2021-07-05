<?php

namespace app\modules\api\models;

use Yii;

/**
 * This is the model class for table "participants".
 *
 * @property int $id
 * @property string $u_id
 * @property int $customerID
 * @property string $userID
 * @property string|null $register_at
 */
class Participant extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'participants';
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
            [['u_id', 'userID'], 'safe'],
            //[['customerID'], 'integer'],
            [['register_at'], 'safe'],
           // [['u_id'], 'string', 'max' => 100],
          //  [['userID'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'u_id' => 'U ID',
            'customerID' => 'Customer ID',
            'userID' => 'User ID',
            'register_at' => 'Register At',
        ];
    }
}
