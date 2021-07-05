<?php

namespace app\modules\api\models;

use Yii;

/**
 * This is the model class for table "participantscredential".
 *
 * @property string|null $lastname
 * @property int $userID
 * @property string|null $email
 * @property string $trainingID
 * @property string $customerID
 * @property int $id
 * @property string|null $firstname
 * @property string $password
 */
class Participantscredential extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'participantscredential';
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
            [[ 'email', 'trainingID', 'password'], 'required']
            // [['userID', 'id'], 'integer'],
            // [['lastname', 'email', 'firstname'], 'string', 'max' => 50],
            // [['trainingID'], 'string', 'max' => 255],
            // [['customerID'], 'string', 'max' => 64],
            // [['password'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'lastname' => 'Lastname',
            'userID' => 'User ID',
            'email' => 'Email',
            'trainingID' => 'Training ID',
            'customerID' => 'Customer ID',
            'id' => 'ID',
            'firstname' => 'Firstname',
            'password' => 'Password',
        ];
    }
}
