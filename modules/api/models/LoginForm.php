<?php

namespace app\modules\api\models;

use Yii;
use yii\base\Model;

use app\modules\api\resources\UserResource;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user This property is read-only.
 *
 */
class LoginForm extends \app\models\LoginForm
{
    /**
     * Finds user by [[username]]
     *
     * @return UserResource|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = UserResource::find()->where(['trainingID' => $this->trainingID])->andWhere(['email'=> $this->email])->one();
        }

        return $this->_user;
    }
}
