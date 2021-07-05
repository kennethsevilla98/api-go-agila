<?php

namespace app\modules\api\controllers;

use yii\rest\ActiveController;
use Yii;
use app\modules\api\models\Participantscredential;
use app\modules\api\models\User;
use yii\web\Response;


class LoginController extends ActiveController
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
        
        $model = new Participantscredential();

        $model->load(Yii::$app->request->post(),'');

        $model->validate();

        if($model->errors){
            Yii::$app->response->statusCode = 400;
            return [
                'msg' => 'email and password cannot be blank'
            ];
        }


        $data = Participantscredential::find()->where(['email' => $model->email])->andWhere(['trainingID'=> $model->trainingID])->one(); 

        if(!$data){
            Yii::$app->response->statusCode = 400;
            return [
                
                'msg' => 'You are not registered here!',
              
            ];
        }

        if (Yii::$app->security->validatePassword($model->password,$data->password)) {


 
             $jwt = Yii::$app->jwt;
             $signer = $jwt->getSigner('HS256');
             $key = $jwt->getKey();
             $time = time();
 
             $token = $jwt->getBuilder()
             ->issuedBy('http://example.com')// Configures the issuer (iss claim)
             ->permittedFor('http://example.org')// Configures the audience (aud claim)
             ->identifiedBy('4f1g23a12aa', true)// Configures the id (jti claim), replicating as a header item
             ->issuedAt($time)// Configures the time that the token was issue (iat claim)
             ->expiresAt($time + 86400)// Configures the expiration time of the token (exp claim)
             ->withClaim('uid', $data->userID)
             ->withClaim('id', $data->id)// Configures a new claim, called "uid"
             ->getToken($signer, $key); // Retrieves the generated token
           
             return $this->asJson([
                 'token' => (string)$token,
                  'user' => [
                      'id' => $data->id,
                      'email' => $data->email,
                      'trainingID' => $data->trainingID,
                      'userID' => $data->userID,
                      'firstname' => $data->firstname,
                      'lastname' => $data->lastname
                    
                  ]
             ]);
 
         }

         else {
            Yii::$app->response->statusCode = 400;
             return [
                'msg' => 'invalid password'
             ];
        
         }
 
         Yii::$app->response->statusCode = 422;
 
         return [
             'erros' => $model->errors,
             'msg' => 'wrong email or password, please check!'
         ];

        }
}