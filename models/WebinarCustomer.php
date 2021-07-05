<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "webinar_customer".
 *
 * @property int $id
 * @property string|null $email
 * @property int|null $no_of_participants
 * @property string|null $u_id
 * @property string $link
 * @property int|null $paid
 * @property int|null $archived
 */
class WebinarCustomer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'webinar_customer';
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
            [['no_of_participants', 'paid', 'archived'], 'integer'],
            [['email'], 'string', 'max' => 100],
            [['u_id', 'link'], 'string', 'max' => 200],
           // [['link'], 'unique'],
            [['u_id'], 'unique'],
            [['link'], 'required']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'no_of_participants' => 'No Of Participants',
            'u_id' => 'U ID',
            'link' => 'Link',
            'paid' => 'Paid',
            'archived' => 'Archived',
        ];
    }
}
