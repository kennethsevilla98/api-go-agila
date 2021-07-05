<?php

namespace app\modules\api\models;

use Yii;

/**
 * This is the model class for table "participantsperwebinar".
 *
 * @property string $trainingID
 * @property int $userid
 * @property int $customerID
 * @property string|null $firstname
 * @property string|null $lastname
 * @property string|null $email
 * @property string $title
 */
class Participantsperwebinar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'participantsperwebinar';
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
            [['trainingID', 'userid', 'title'], 'required'],
            [['userid', 'customerID'], 'integer'],
            [['trainingID'], 'string', 'max' => 100],
            [['firstname', 'lastname', 'email'], 'string', 'max' => 50],
            [['title'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'trainingID' => 'Training ID',
            'userid' => 'Userid',
            'customerID' => 'Customer ID',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'email' => 'Email',
            'title' => 'Title',
        ];
    }
}
