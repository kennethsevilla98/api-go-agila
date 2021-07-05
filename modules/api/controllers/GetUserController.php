<?php

namespace app\modules\api\controllers;

use yii\rest\ActiveController;
use Yii;
use app\modules\api\models\Participantscredential;
use yii\web\Response;
use sizeg\jwt\Jwt;
use sizeg\jwt\JwtHttpBearerAuth;
use yii\filters\ContentNegotiator;



class GetUserController extends ActiveController
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

    // public function behaviors()

	// {

    //     $behaviors = parent::behaviors();

    //     $behaviors['contentNegotiator'] = [

    //         'class' => 'yii\filters\ContentNegotiator',

    //         'formats' => [

    //             'application/json' => Response::FORMAT_JSON,

    //         ]

    // ];

    // $behaviors['authenticator'] = [
    //     'class' => JwtHttpBearerAuth::class,
        
    // ];

    //     $behaviors['corsFilter'] = [
    //         'class' => \yii\filters\Cors::className(),
    //         'cors'  => [
    //             // restrict access to domains:
    //             'Origin'=> static::allowedDomains(),
    //             'Access-Control-Request-Method'    => ['GET','OPTION','HEAD'],
    //             'Access-Control-Allow-Credentials' => false,
    //             'Access-Control-Max-Age'           => 36000,// Cache (seconds)
    //             'Access-Control-Request-Headers' => ['*','authorization','x-auth-token'],
    //             'Access-Control-Allow-Origin' => ['*'],

    //         ],
    //     ];

        
  

    //     return $behaviors;

	// }	

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

    public function actionIndex(){
        
        $headers = Yii::$app->request->headers['x-auth-token'];
        // return $headers['authorization'];
       // $token = Yii::$app->jwt->getParser()->parse((string) $token); // Parses from a string
        //$token->getHeaders(); // Retrieves the token header

 
      

 
        $token = Yii::$app->jwt->getParser()->parse((string) $headers); // Parses from a string
       // $token->getHeaders(); // Retrieves the token header
        $get= $token->getClaims(); // Retrieves the token claims
        //return $get['uid'];


        

        $data = Participantscredential::findOne(['id' => $get['id']]);
        
        if($data != null){
            return [

                'email' => $data->email,
                'firstname' => $data->firstname,
                'lastname' => $data->lastname,
                'link' => $data->link,
                'trainingID'=> $data->trainingID
            ];
        }
        
        Yii::$app->response->statusCode = 422;

        return [
            'erros' => $data->errors
        ];
        
        }
}