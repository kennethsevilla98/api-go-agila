<?php

namespace app\modules\api\controllers;
use app\modules\api\models\LoginForm;
use app\modules\api\models\SignupForm;
use app\modules\api\models\WebinarCustomer;


use yii\rest\Controller;
use Yii;
use sizeg\jwt\Jwt;
use sizeg\jwt\JwtHttpBearerAuth;


class UserController extends Controller
{

    

    /**
     * @inheritdoc
     */
    // public function behaviors()
    // {
    //     $behaviors = parent::behaviors();
    //     $behaviors['authenticator'] = [
    //         'class' => JwtHttpBearerAuth::class,
    //         'optional' => [
    //             'login',
    //         ],
    //     ];

    //     return $behaviors;
    // }

    /**
     * @return \yii\web\Response
     */

    public function actionIndex()
    {
        return $this->render('index');
        var_dump(Yii::$app->user->identity);die;
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
             //return $this->goHome();

             return $this->asJson([
                'status' => 'you are already login',
            ]);
        }

        $model = new LoginForm();

  

        if ($model->load(Yii::$app->request->post(), '') && $model->login()) {


           $uid = $model->getUser()->toArray(['id']);

            $jwt = Yii::$app->jwt;
            $signer = $jwt->getSigner('HS256');
            $key = $jwt->getKey();
            $time = time();

            $token = $jwt->getBuilder()
            ->issuedBy('http://example.com')// Configures the issuer (iss claim)
            ->permittedFor('http://example.org')// Configures the audience (aud claim)
            ->identifiedBy('4f1g23a12aa', true)// Configures the id (jti claim), replicating as a header item
            ->issuedAt($time)// Configures the time that the token was issue (iat claim)
            ->expiresAt($time + 300)// Configures the expiration time of the token (exp claim)
            ->withClaim('uid', $uid['id'])// Configures a new claim, called "uid"
            ->getToken($signer, $key); // Retrieves the generated token
          
            return $this->asJson([
                'token' => (string)$token,
                 $uid['id']
            ]);

        }

        Yii::$app->response->statusCode = 422;

        return [
            'erros' => $model->errors,
            'msg' => 'wrong email or password, please check'
        ];
        
        // $jwt = Yii::$app->jwt;
        // $signer = $jwt->getSigner('HS256');
        // $key = $jwt->getKey();
        // $time = time();

        // $token = $jwt->getBuilder()
        // ->issuedBy('http://localhost')// Configures the issuer (iss claim)
        // ->permittedFor('http://localhost')// Configures the audience (aud claim)
        // ->identifiedBy('4f1g23a12aa', true)// Configures the id (jti claim), replicating as a header item
        // ->issuedAt($time)// Configures the time that the token was issue (iat claim)
        // ->expiresAt($time + 3600)// Configures the expiration time of the token (exp claim)
        // ->withClaim('uid', 100)// Configures a new claim, called "uid"
        // ->getToken($signer, $key); // Retrieves the generated token

        // return $this->asJson([
        //     'token' => (string)$token,
        // ]);
        
    }




    public function actionSignup()
    {
       $model = new SignupForm(); 

       if ($model->load(Yii::$app->request->post(), '') && $model->register()) {
           return $model->_user;
       }

       Yii::$app->response->statusCode = 422;

        return [
            'erros' => $model->errors
        ];

    }    

    public function actionData()
    {
        return $this->asJson([
            'success' => true,
        ]);
    }


    public function actionGetCustomerByLink(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $customer = new WebinarCustomer();
        //$customer->scenario = WebinarCustomer::SCENARIO_CREATE;
       


        $customer->load(Yii::$app->request->post(),'');


            if($customer->validate()){
            
                $data = WebinarCustomer::findOne(['link'=>$customer->link]);
    
                return [
                    'data' => $customer->link
                ];
    
            
        }
        

    


    }


}
