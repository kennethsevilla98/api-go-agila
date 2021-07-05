<?php

namespace app\modules\api\controllers;

use yii\rest\ActiveController;
use Yii;
use app\modules\api\models\CustomersView;
use app\modules\api\models\Participantsperwebinar;
use yii\web\Response;

use sizeg\jwt\Jwt;
use sizeg\jwt\JwtHttpBearerAuth;

class CustomerController extends ActiveController
{
   
    public $modelClass = 'app\modules\api\models\WebinarCustomer';

   // public $enableCsrfValidation = true;


    public static function allowedDomains()
{
    return [
       '*',   // star allows all domains
       'http://localhost:3000',
       'https://eroom.itdi.ph'
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

        // $behaviors['authenticator'] = [
        //             'class' => JwtHttpBearerAuth::class,
        //             'optional' => [
        //                 'login',
        //             ],
        //         ];
        

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
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new CustomersView();

        $model->load(Yii::$app->request->post(), '');

        $customer = CustomersView::findOne(['link' => $model->link]);

    
      //  $participants = array();
        if($customer->customerID != null || $customer->trainingID != null)
        $participants = Participantsperwebinar::find()->where(['customerID' => $customer->customerID])
        ->andWhere(['trainingID' => $customer->trainingID])
        ->andWhere(['link'=>$model->link])
        ->all();

        //res.json({customers:data,participants: participants, title: data[0].title, dateStart: data[0].dateStart, slot: data[0].noofpart})

        

        if($model->link != '' || $model->link != null){
            return [
                'customers'=> [$customer],    
                'participants' => $participants,
                'title' => $customer->title,
                'dateStart' => $customer->datesched,
                'slot' => $customer->no_of_participants, 
            ];
        }
        else
        {
            return [ 'message' => 'errors'];
        }

    }
}