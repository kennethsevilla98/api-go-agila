<?php

namespace app\modules\api\models;

use Yii;

/**
 * This is the model class for table "customersview".
 *
 * @property int|null $customerID
 * @property string|null $u_id
 * @property string $title
 * @property int|null $no_of_participants
 * @property string|null $email
 * @property string $link
 * @property string $description
 * @property string|null $datesched
 */
class CustomersView extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customersview';
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
            [['customerID', 'no_of_participants'], 'integer'],
            [['description'], 'string'],
            [['u_id', 'title', 'link'], 'string', 'max' => 200],
            [['email', 'datesched'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'customerID' => 'Customer ID',
            'u_id' => 'U ID',
            'title' => 'Title',
            'no_of_participants' => 'No Of Participants',
            'email' => 'Email',
            'link' => 'Link',
            'description' => 'Description',
            'datesched' => 'Datesched',
        ];
    }
}
