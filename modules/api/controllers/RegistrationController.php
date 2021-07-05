<?php

namespace app\modules\api\controllers;

use yii\rest\ActiveController;
use Yii;
use app\modules\api\models\CustomersView;
use app\modules\api\models\Usersprofile;
use app\modules\api\models\Trainingsandseminars;
use app\modules\api\models\User;
use app\modules\api\models\Participantsperwebinar;
use app\modules\api\models\Participant;
use yii\web\Response;

class RegistrationController extends ActiveController
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
            unset($actions['delete'], $actions['create'],$actions['update'],
            $actions['index']
            );

            // // customize the data provider preparation with the "prepareDataProvider()" method
            // $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

            return $actions;
        }

    public function actionCreate(){
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new CustomersView();

        $body = Yii::$app->request->post();
 

        
            
        


        $slotValidation = Participantsperwebinar::find()->where(['customerID'=>$body['customerID']])
                            ->andWhere(['trainingID'=>$body['trainingID']])
                            ->andWhere(['link'=>$body['link']])
                            ->all();

        $count = count($slotValidation);

        // return [
        //     $count,
        //     $body
        // ];

       
        if($count >= $body['slot']){
            return [
                'notification' => 'no slot available'
            ];
        }
        else {
            $userValidation = Usersprofile::find()->andWhere(['email' => $body['email']])->all();

            extract($body);
            if( $email == "" ||
                $password == "" ||
                $lastname == "" ||
                $firstname =="" ||
                $contactno =="" ||
                $gender == "" ||
                $areyou == "" ||
                $sector == "" ||
                $slot == ""
            ){
                return ['notification'=> 'Please Complete!'];
            }
            
            if($userValidation == null){

                
               
                $transaction = Yii::$app->db->beginTransaction();

                try
                {
                   
                $security = \Yii::$app->security;

                $user = new User();
                $user->username = $email;
                $user->password_hash =  $security->generatePasswordHash($password);

                $user->save();

                $userprofile = new Usersprofile();
                $userprofile->userid = $user->id;
                $userprofile->email = $user->username;
                $userprofile->lastname = $lastname;
                $userprofile->firstname = $firstname;
                $userprofile->contactno = $contactno;
                $userprofile->gender = $gender;
                $userprofile->agegroup = $agegroup;
                $userprofile->region = $region;
                $userprofile->areyou = $areyou;
                $userprofile->sector = $sector;

                $userprofile->save();

                
                $transaction->commit();
                }
                catch(\Exception $e)
                {
                    $transaction->rollBack();
                    return [
                        'message' => 'data was not save'
                    ];
                }
                
                $participant = new Participant();

                $participant->userID = $user->id; 
                $participant->customerID = $customerID;
                $participant->u_id = $trainingID;
                $participant->link = $link;
                
               if($participant->save()){
                   
                    $webinar = Trainingsandseminars::findOne(['url'=>$trainingID]);
                    $counter = 0;
                    do {
                        $variable = Yii::$app->mailer->compose()
                        ->setFrom(Yii::$app->params['senderEmail'])
                        ->setTo($email)
                        ->setSubject($webinar->title)
                        ->setHtmlBody(
                            "Thank you for registering!

                            Here's the link to" .$webinar->title .":".
                            
                            Yii::$app->params['roomLink'].$webinar->url 
                        )
                        ->send();
                        
                        $counter++;
                    } while ($variable == false && $counter <= 10);

                    return ['notification' => 'email sent'];
                }
                else{
                    return ['data' => 'not save'];
                }
                

            }
            else{
                return[ 'mesage' => 'you are already registered!'];
            }
        }

      
    }
}