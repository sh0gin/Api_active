<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'fdisgusdf90pgsdf',
            'baseUrl' => '',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
                'multipart/form-data' => 'yii\web\MultipartFormDataParser'
            ]
        ],
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8',
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                if ($response->statusCode == 404) {
                    $response->data = [
                        'message' => 'not found',
                    ];
                }
                if ($response->statusCode == 402) {
                    $response->data = [
                        'message' => 'forbideen for you',
                    ];
                }
                if ($response->statusCode == 401) {
                    $response->data = [
                        'message' => 'login failed',
                    ];
                }
                if ($response->statusCode == 403) {
                    $response->data = [
                        'message' => 'login failed',
                    ];
                }
            },
            'formatters' => [
                \yii\web\Response::FORMAT_JSON => [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG, // use "pretty" output in debug mode
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                    // ...
                ],
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'enableSession' => false,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                ['class' => 'yii\rest\UrlRule', 'controller' => 'user'],


                // 'rules' => [
                //                 [
                //                     'class' => 'yii\rest\UrlRule', 'controller' => 'user',
                //                     'controller' => 'post',
                //                     'prefix' => 'api',
                //                     'pluraralize' => true,
                //                     'extraPatterns' => [
                //                         'POST registration' => 'register',
                //                         'POST registration' => 'register',
                //                     ]
                //                 ], были попытки :)

                "POST api/registration" => 'user/register',
                "OPTIONS api/registration" => 'user/options',

                "POST api/logout" => 'user/logout',
                "OPTIONS api/logout" => 'user/options',

                "POST api/login" => 'user/login',
                "OPTIONS api/login" => 'user/options',

                "POST api/books/upload" => 'book/uploadsbook',
                "OPTIONS api/books/upload" => 'book/options',

                "GET api/books" => 'book/get-books',
                "OPTIONS api/books" => 'book/options',
                
                // "GET api/books" => 'book/get-books-pagination', no need anymore
                // "OPTION api/books" => 'book/option',

                "GET api/books/progress" => 'book/get-books-progress',
                "OPTIONS api/books/progress" => 'book/options',

                "GET api/books/<id>" => 'book/get-info-book',
                "OPTIONS api/books/<id>" => 'book/options',

                // "GET api/books/<id>" => 'book/get-books-from-user',
                // "OPTION api/books/" => 'book/option',
                // "GET api/books/" => 'book/get-info-book-from-user',
                // "OPTION api/books/" => 'book/option',

                "DELETE api/books/<id>" => 'book/delete-book',
                "OPTIONS api/books/<id>" => 'book/options',

                "PATCH api/books/<id>" => 'book/edit-book',
                "OPTIONS api/books/<id>" => 'book/options',

                "POST api/books/<id>/progress" => 'book/save-progress',
                "OPTIONS api/books/<id>/progress" => 'book/options',

                "GET api/books/<id>/progress" => 'book/get-progress',
                "OPTIONS api/books/<id>/progress" => 'book/options',

                "POST api/user/settings" => 'book/set-settings',
                "OPTIONS api/user/settings" => 'book/options',

                "PUT api/books/<id>/change-visibility" => 'book/change-visibility',
                "OPTIONS api/books/<id>/change-visibility" => 'book/options'


            ],
        ]
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['*'],
    ];
}

return $config;
