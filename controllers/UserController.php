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
                'login' => [
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

            if (array_key_exists('gender', Yii::$app->request->post())) {

                $model->gender = Gender::getGender(Yii::$app->request->post()['gender']);
            }

            $model->save(false);

            Yii::$app->response->statusCode = 200;


            return $this->asJson([
                'data' => [
                    'user' => [
                        'id' => $model->id,
                        'name' => $model->name,
                        'email' => $model->email,
                    ]
                ],
                'code' => 200,
                'message' => "Пользователь создан",
            ]);
        } else {
            Yii::$app->response->statusCode = 422;
            $valid = $model->getErrors();
            
            return $this->asJson([
                'errors' => [
                    'code' => 422,
                    'message' => "Validation error",
                    'errors' => [
                        $valid,
                    ]
                ],
            ]);
        }
    }
}
