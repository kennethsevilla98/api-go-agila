<?php

namespace app\modules\api\controllers;

use yii\rest\ActiveController;
use Yii;
use app\modules\api\models\Attendance;
use yii\web\Response;
use sizeg\jwt\Jwt;
use sizeg\jwt\JwtHttpBearerAuth;
use yii\filters\ContentNegotiator;



class TimeInController extends ActiveController
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

    public function actionCreate(){
        
   

        $attendance = new Attendance();

        $body = Yii::$app->request->post();

        if(!$body)
        {
            return [
                'msg' => 'Cannot accept blank data.'
            ];
        }
        
        $attendance->trainingID = $body['trainingID'];
        $attendance->email = $body['email'];
        $attendance->name = $body['name'];
        $attendance->time_in = date("Y-m-d H:i:s");

        $attendance->save();
        
 

        return [
            'msg' => 'Time in successfully.'
        ];
        
        }
}