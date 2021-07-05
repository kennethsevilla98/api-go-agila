<?php

namespace app\modules\api\controllers;

use yii\rest\ActiveController;
use Yii;
use app\modules\api\models\Usersprofile;
use app\modules\api\models\Participant;
use app\modules\api\models\Trainingsandseminars;
use app\modules\api\models\Participantsperwebinar;
use yii\web\Response;


class ValidateEmailController extends ActiveController
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
                'Access-Control-Max-Age'           => 3600,// Cache (seconds)
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
            unset($actions['delete'], $actions['create'],$actions['update']);

            // // customize the data provider preparation with the "prepareDataProvider()" method
            // $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

            return $actions;
        }

    public function actionCreate(){
        

        $model = Yii::$app->request->post(); //fetch json
       
        if(!$model['email']){
            return [
                'notification' => 'fillup'
            ];
        }

       
        
        $data = Participantsperwebinar::find()->where(['customerID' => $model['customerID']])
                                        ->andWhere(['trainingID' => $model['trainingID']])
                                        ->andWhere(['link'=>$model['link']])
                                        ->all();

        // return $userprofile;

        
        $count = count($data);
        

        if($count >= $model['slot']) {
            return [
                'notification' => 'no slot available'
            ];
        }
        else 
        {
            $checkemail = new Usersprofile();

            $checkemail->load(Yii::$app->request->post(),'');
    
           
    
           
            //verify email
            if( !$checkemail->validate()){

               
                return[
                    
                    'notification' => 'invalid email'
                ];
            }

            $userprofile = Usersprofile::findOne(['email'=>$model['email']]);

            //verify if user is not existing
            if($userprofile == null){
                return [ 'notification'=>'next'];
            }
            
            else{
                
                $checkParticipants = Participantsperwebinar::find()->where(['customerID' => $model['customerID']])
                                                                    ->andWhere(['trainingID' => $model['trainingID']])
                                                                    ->andWhere(['email' => $model['email']])
                                                                    ->one();



                if($checkParticipants == null)
                {
                    $participant = new Participant();
                    $participant->customerID = $model['customerID'];
                    $participant->userID = $userprofile->userid;
                    $participant->u_id = $model['trainingID'];
                    $participant->link = $model['link'];
                   ;

                    

                    if( $participant->save()){

                        $webinar = Trainingsandseminars::findOne(['url'=>$model['trainingID']]);


                        $counter = 0;
                        do {
                            $variable = Yii::$app->mailer->compose()
                            ->setFrom('customer@itdi.dost.gov.ph')
                            ->setTo($model['email'])
                            ->setSubject($webinar->title)
                            ->setHtmlBody(
                                "Thank you for registering!

                                Here's the link to" .$webinar->title .":".
                                Yii::getAlias('@urlLink').$webinar->url 
                            )
                            ->send();
                            
                            $counter++;
                        } while ($variable == false && $counter <= 10);

                        return ['notification' => 'email sent'];
                    }

                    else{
                            return [
                                $participant->errors
                            ];
                        }


                   
                    //email send

                    // $variable = Yii::$app->mailer->compose()
                    // ->setFrom('kennethsevilla98@gmail.com   ')
                    // ->setTo('kgtsevilla@itdi.dost.gov.ph')
                    // ->setSubject('Title')
                    // ->setHtmlBody(
                    //     'Body
                    //     '
                    // )
                    // ->send();
                    // else{
                    //     return [
                    //         $participant->errors
                    //     ];
                    // }
                    
    
                } 
                else
                {

                    
                 //   return $variable;
               
                    return [
 //                        'sent'=>$variable,
                        'notification'=> 'You are already registered in this webinar'
                    ];
                }
               
            }
           
        }

     
    }

      


    //     $data = Participantsperwebinar::find()->where(['customerID' => $customer->customerID])->andWhere(['trainingID' => $customer->trainingID])->all();

    //     $count = count($data);

    //    // if($count >= $model->)
        
    //     Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    //     $model = new CustomersView();

    //     $model->load(Yii::$app->request->post(), '');

    //     $customer = CustomersView::findOne(['link' => $model->link]);

    //     $participants = Participantsperwebinar::find()->where(['customerID' => $customer->customerID])->andWhere(['trainingID' => $customer->trainingID])->one();

    //     //res.json({customers:data,participants: participants, title: data[0].title, dateStart: data[0].dateStart, slot: data[0].noofpart})

    //     if($model->link != '' || $model->link != null){
    //         return [
    //             'customers'=> [$customer],  
    //             'participants' => [$participants],
    //             'title' => $customer->title,
    //             'dateStart' => $customer->datesched,
    //             'slot' => $customer->no_of_participants, 
    //         ];
    //     }
    //     else
    //     {
    //         return [ 'message' => 'errors'];
    //     }

    // }
}