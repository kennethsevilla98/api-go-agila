<?php

namespace app\modules\api\models;

use Yii;

/**
 * This is the model class for table "usersprofile".
 *
 * @property int $id
 * @property int $userid
 * @property string|null $firstname
 * @property string|null $lastname
 * @property string|null $contactno
 * @property string|null $gender
 * @property string|null $agegroup
 * @property string|null $region
 * @property string|null $areyou
 * @property string|null $email
 * @property string $date_updated
 * @property string|null $sector
 */
class Usersprofile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usersprofile';
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
            ['email','email'],
            [['userid'], 'integer'],
            [['date_updated'], 'safe'],
            [['firstname', 'lastname', 'contactno', 'gender', 'agegroup', 'region', 'areyou', 'email', 'sector'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userid' => 'Userid',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'contactno' => 'Contactno',
            'gender' => 'Gender',
            'agegroup' => 'Agegroup',
            'region' => 'Region',
            'areyou' => 'Areyou',
            'email' => 'Email',
            'date_updated' => 'Date Updated',
            'sector' => 'Sector',
        ];
    }
}
