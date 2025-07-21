<?php

namespace app\controllers;

use app\models\Gender;
use app\models\Role;
use app\models\User;
use Yii;
use yii\filters\auth\HttpBearerAuth;

class UserController extends \yii\rest\ActiveController
{

    public $modelClass = '';
    public $enableCsrfValidation = '';

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // remove authentication filter
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);

        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
            'cors' => [
                'Origin' => [isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_OROGIN'] : 'http://' . $_SERVER['REMOTE_ADDR']],
                // 'Origin' => ["*"],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
            ],
            'actions' => [
                'logout' => [
                    'Access-Control-Allow-Credentials' => true,
                ]
            ]
        ];
        $auth = [
            'class' => HttpBearerAuth::class,
            'only' => ['logout']
        ];
        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();

        // disable the "delete" and "create" actions
        unset($actions['delete'], $actions['create']);

        // customize the data provider preparation with the "prepareDataProvider()" method
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }

    public function actionRegister()
    {
        // print_r(Yii::$app->request->post());
        $model = new User();
        $model->scenario = 'reg';
        $model->load(Yii::$app->request->post(), '');
        if ($model->validate()) {
            $model->password = Yii::$app->security->generatePasswordHash($model->password);
            $model->role_id = Role::getRoleId('User');


            $model->save(false);

            Yii::$app->response->statusCode = 201;


            return $this->asJson([
                'data' => [
                    'user' => [
                        'id' => $model->id,
                        'name' => $model->name,
                        'email' => $model->email,
                    ]
                ],
                'code' => 201,
                'message' => "Пользователь создан",
            ]);
        } else {
            Yii::$app->response->statusCode = 422;
            return $this->asJson([
                'errors' => [
                    'code' => 422,
                    'message' => "Validation error",
                    'errors' => [
                        $model->getErrors(),
                    ]
                ],
            ]);
        }
    }

    public function actionLogin()
    {
        $model = new User();
        $model->scenario = 'auth';
        $model->load(Yii::$app->request->post(), "");

        if ($model->validate()) {
            $user = User::findOne(['email' => $model->email]);
            if ($user && $user->validatePassword($model->password)) {
                $user->token = Yii::$app->security->generateRandomString();
                // $user->role_id = Role::getRoleId('user');
                $user->save(false);
                return $this->asJson([
                    'data' => [
                        'token' => $user->token,
                        'user' => [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                            'role' => Role::getRoleName($user->role_id),
                        ]
                    ],
                    'code' => 200,
                    'message' => "Успешная авторизация",
                ]);
            } else {
                Yii::$app->response->statusCode = 403;
                return $this->asJson([
                    'massage' => 'Login failed',
                ]);
            }
        } else {
            Yii::$app->response->statusCode = 422;

            return $this->asJson([
                'errors' => [
                    'code' => 422,
                    'message' => "Validation error",
                    'errors' => [
                        $model->getErrors(),
                    ]
                ],
            ]);
        }
    }

    public function actionLogout() {
        $user = User::findOne(Yii::$app->user->id);
        $user->token = NULL;
        $user->save();
    }
}
