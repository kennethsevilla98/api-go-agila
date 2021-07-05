<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db/db.php';
$itdidb_cportal = require __DIR__ . '/db/itdidb_cportal.php';
$itdidb_eroom = require __DIR__ . '/db/itdidb_eroom.php';

$config = [
    'id' => 'basic',
    'timezone'=> 'Asia/Manila',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
       
    ],
    'modules' => [
        'api' => [
            'class' => 'app\modules\api\Modules',
        ],
    ],
    'components' => [
        'jwt' => [
            'class' => \sizeg\jwt\Jwt::class,
            'key' => 'asldfhkjahu23i3iuxl',
            // You have to configure ValidationData informing all claims you want to validate the token.
            'jwtValidationData' => \app\components\JwtValidationData::class,
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'R4bo-PGm5RJwZADsUClO3yDQqCXDGGAK',
            'parsers' => [
                'application/json' => \yii\web\JsonParser::class
            ]
        ], 
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false,
            'enableSession' => false,
            'loginUrl' => null,
        ],
        //'loginUrl' => null,
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtpout.asia.secureserver.net',
                'username' => 'kennethsevilla98@gmail.com',
                'password' => '',
                'port' => '80',
                'encryption' => '',
            ]
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    
                ],
            ],
        ],
        'db' => $db,
        'itdidb_cportal'=>$itdidb_cportal,
        'itdidb_eroom' => $itdidb_eroom,
    
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            
            'rules' => [
                ['class'=>'yii\rest\UrlRule',
                 'controller' =>
                 [
                     'api/customer',
                     'api/validate-email',
                     'api/registration',
                     'api/pw-reset',
                     'api/training-link',
                     'api/login',
                     'api/get-user',
                     'api/reset-password',
                     'api/time-in',
                     'api/time-out'
                     ] 
                ]
            ],
        ],

        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8',
            // ...
        ],

        
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
