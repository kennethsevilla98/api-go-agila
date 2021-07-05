<?php

namespace app\modules\api\controllers;

use yii\rest\ActiveController;
use Yii;
use app\modules\api\models\Trainingsandseminars;
use yii\web\Response;

class TrainingLinkController extends ActiveController
{
   
    public $modelClass = 'app\modules\api\models\WebinarCustomer';


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
                'Access-Control-Request-Method'    => ['POST','GET','PUT','OPTIONS'],
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
            unset($actions['delete'], $actions['create'],$actions['update']
            //$actions['index']
            );

            // // customize the data provider preparation with the "prepareDataProvider()" method
            // $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

            return $actions;
        }

    public function actionCreate(){
        

        $body = Yii::$app->request->post();

    
        $trainingSeminar = Trainingsandseminars::find()->andWhere(['url'=>$body['url']])->all();
        
        return $trainingSeminar;
        
    }
}