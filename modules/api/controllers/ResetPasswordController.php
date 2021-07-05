<?php

namespace app\modules\api\controllers;

use yii\rest\ActiveController;
use Yii;
use app\modules\api\models\User;
use yii\web\Response;
use sizeg\jwt\Jwt;
use sizeg\jwt\JwtHttpBearerAuth;
use yii\filters\ContentNegotiator;



class ResetPasswordController extends ActiveController
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

        $behaviors['contentNegotiator'] = [

            'class' => ContentNegotiator::className(),

            'formats' => [

                'application/json' => Response::FORMAT_JSON,

            ],

        ];

        // remove authentication filter

        $auth = $behaviors['authenticator'];

        unset($behaviors['authenticator']);

        // add CORS filter

        $behaviors['corsFilter'] = [

        'class' => \yii\filters\Cors::className(),

            ];

        // re-add authentication filter

        $behaviors['authenticator'] = $auth;

        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)

        $behaviors['authenticator']['except'] = ['options'];

        $behaviors['authenticator'] = [

            'class' => JwtHttpBearerAuth::class,




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
        
        $headers = Yii::$app->request->headers['x-auth-token'];
        // return $headers['authorization'];
       // $token = Yii::$app->jwt->getParser()->parse((string) $token); // Parses from a string
        //$token->getHeaders(); // Retrieves the token header

 
      
        $body = Yii::$app->request->post();
 
        $token = Yii::$app->jwt->getParser()->parse((string) $headers); // Parses from a string
       // $token->getHeaders(); // Retrieves the token header
    
        //return $get['uid'];


        if($token){
            $get= $token->getClaims(); 

           
            // Retrieves the token claims
            $user = User::findOne(['id'=>$get['uid']]);

            if($body['confirm'] == ''){
                return [
                    'msg' => 'cannot be blank'
                ];
            }

        
            if($user){
               $user->password_hash = Yii::$app->security->generatePasswordHash($body['confirm']);

               
                
              if($user->save()){
                  return [
                    'msg'=>'password change sucess'
                  ];
              } 

              //errors
              return[
                  'msg'=>'password change fail'
              ];
            }
        
        Yii::$app->response->statusCode = 422;
        }
        

        return [
            'erros' => $data->errors
        ];
        
        }
}