<?php

namespace app\modules\api\models;
use app\models\Usersprofile;

use Yii;
use yii\base\Model;

use app\modules\api\resources\UserResource;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user This property is read-only.
 *
 */
class SignupForm extends Model
{
    public $username;
    public $password;
    public $password_repeat;

    public $email;
    public $firstname;
    public $lastname;
    public $gender;
    public $agegroup;
    public $region;
    public $contactno;
    public $ofw;
    public $sector;
    public $pw;
    public $id;
    public $rememberMe = true;

    public $_user = false;
    public $_userprofile = false;
    


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 
            'password',
            'password_repeat', 
            'lastname',
            'gender',
            'agegroup',
            'region',
            'contactno',
            ],
             'required'],

            ['password', 'compare', 'compareAttribute' => 'password_repeat'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function register()
    {

        $transaction = Yii::$app->db->beginTransaction();

        $this->_user = new UserResource();
        $this->_userprofile = new Usersprofile();
        if($this->validate()){

            try
            {
                $security = \Yii::$app->security;
                $this->_user->username = $this->username;
                $this->_user->password_hash = $security->generatePasswordHash($this->password);
                
                $this->_user->save();

                $this->_userprofile->userid = $this->_user->id;
                $this->_userprofile->email = $this->_user->username;
                $this->_userprofile->firstname = $this->firstname;
                $this->_userprofile->lastname = $this->lastname;
                $this->_userprofile->gender = $this->gender;
                $this->_userprofile->agegroup = $this->agegroup;
                $this->_userprofile->region = $this->region;
                $this->_userprofile->contactno = $this->contactno;
                // $this->userprofile->ofw = $this->ofw;
                // $this->userprofile->sector = $this->sector;
                // $this->userprofile->pwd = $this->pwd;

                $this->_userprofile->save();

                $transaction->commit();



            }
            catch(Exception $e)
            {
                $transaction->rollBack();
            }
            

            
            if($this->_user->save()){
                return true;
            }
            return false;
        }
        return false;

    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::find()->where(['id' => $this->id])->andWhere(['username'=> $this->username])->one();
        }

        return $this->_user;
    }
}
