<?php

namespace app\modules\api\controllers;

use yii\rest\ActiveController;
use Yii;
use app\modules\api\models\CustomersView;
use app\modules\api\models\User;
use yii\web\Response;

use sizeg\jwt\Jwt;
use sizeg\jwt\JwtHttpBearerAuth;

class PwResetController extends ActiveController
{
   
  
    public $modelClass = 'app\modules\api\models\WebinarCustomer';

   // public $enableCsrfValidation = true;


    public static function allowedDomains()
        {
            return [
            '*',   // star allows all domains
            'http://localhost:3000',
            ];
        }  

    public function behaviors()

	{

        $behaviors = parent::behaviors();

        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors'  => [
                // restrict access to domains:
                'Origin'=> static::allowedDomains(),
                'Access-Control-Request-Method'    => ['POST'],
                'Access-Control-Allow-Credentials' => false,
                'Access-Control-Max-Age'           => 36000,// Cache (seconds)
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Origin' => false,

            ],
        ];

        $behaviors['contentNegotiator'] = [

	            'class' => 'yii\filters\ContentNegotiator',

	            'formats' => [

	                'application/json' => Response::FORMAT_JSON,

	            ]

        ];

        

        return $behaviors;

	}	

    public function actions()
        {
            $actions = parent::actions();

            // disable the "delete" and "create" actions
            unset($actions['delete'], $actions['create'],$actions['update'],$actions['index']
            //$actions['index']
            );

            // // customize the data provider preparation with the "prepareDataProvider()" method
            // $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

            return $actions;
        }

    public function actionCreate(){
        
        

        $body = Yii::$app->request->post();

       
       

        if($body && $body['email'] != ''){

            $emailCheck = new User();

            $emailCheck->username = $body['email'];


            $validateEmail = $emailCheck->validate();

          

            if(!$validateEmail){
                return[
                    'msg'=>'invalid email'
                ];
            }


            $model = User::findOne(['username' => $body['email']]);

            
            //set token
            $jwt = Yii::$app->jwt;
            $signer = $jwt->getSigner('HS256');
            $key = $jwt->getKey();
            $time = time();

            $token = $jwt->getBuilder()
            ->issuedBy('http://example.com')// Configures the issuer (iss claim)
            ->permittedFor('http://example.org')// Configures the audience (aud claim)
            ->identifiedBy('4f1g23a12aa', true)// Configures the id (jti claim), replicating as a header item
            ->issuedAt($time)// Configures the time that the token was issue (iat claim)
            ->expiresAt($time + 900)// Configures the expiration time of the token (exp claim) @15minutes
            ->withClaim('uid', $model->id)// Configures a new claim, called "uid"
            ->getToken($signer, $key); // Retrieves the generated token
          

            //send link to email 
            $counter = 0;

            do {
                $variable = Yii::$app->mailer->compose()
                ->setFrom(Yii::$app->params['senderEmail'])
                ->setTo($body['email'])
                ->setSubject('Password Reset')
                ->setHtmlBody(
                    "Reset your password here. 
                    <br>
                    <br>"
                    .
                    Yii::$app->params['resetPWLink'].$token 
                )
                ->send();
                
                $counter++;
            } while ($variable == false && $counter <= 10);

            if($variable){
              
                return [
                    'msg' => 'Password Reset Sent to your email.'
                ];
            }

            else {
                return [
                    'msg' => 'Error! Please try again later.'
                ];
            }
            
        }

        

    return [
        'msg' => 'email cannot be blank'
    ];
    
     }
}