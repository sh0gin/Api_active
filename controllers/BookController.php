<?php

namespace app\controllers;

use app\models\Book;
use app\models\File;
use app\models\User;
use yii\db\Query;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\web\UploadedFile;

class BookController extends \yii\rest\ActiveController
{

    public $modelClass = '';
    public $enableCsrfValidation = '';

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
            'only' => ['logout', 'uploadsbook']
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
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionUploadsbook()
    {
        $model = new Book();
        $model->scenario = 'book';
        $model->load(Yii::$app->request->post(), "");
        $model->file_id = UploadedFile::getInstancesByName('file_id');
        
        if ($model->validate()) {
            if (Yii::$app->user->id) {
                $model_book = new File();
                $model_book->user_id = Yii::$app->user->id;
                $model_book->file_url = $model->upload($model->file_id);

                $model_book->save(false);
                $model->file_id = $model_book->id;
                $model->save();

                Yii::$app->response->statusCode = 201;
                return $this->asJson([
                    'data' => [
                        'book' => [
                            'id' => $model->id,
                            'title' => $model->title,
                            'author' => $model->autor,
                            'description' => $model->description,
                            'file_url' => $model->file_id,
                        ]
                    ],
                    'code' => 201,
                    'message' => "Книга успешно загружена",
                ]);
            } else {
                Yii::$app->response->statusCode = 403;
                return $this->asJson([
                    'massage' => 'Login failed',
                ]);
            }
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

        // $autor_id = $user->findOne(['token' => $post['token']])->id; // вместо токена, даём id польхзователя
        // var_dump($autor_id);

        // return (Yii::$app->request->post());
    }

    public function actionGetBooks()
    {
        $query = new Query;

        $query = $query->select('*')
            ->from('file')
            ->leftJoin('user', 'file.user_id = user.id')
            ->rightJoin('book', 'file.id = book.file_id')
            ->where(['role_id' => 2])
            ->createCommand()
            ->queryAll();
        $result = [];
        Yii::$app->response->statusCode = 200;
        foreach ($query as $model) {
            $result[] = [
                'id' => $model['id'],
                'title' => $model['title'],
                'autor' => $model['autor'],
                'description' => $model['description'],
                'file_url' => $model['file_url'],
            ];
        }
        return $this->asJson([
            'data' => [
                'books' => [
                    $result,
                ],
                'code' => 200,
                'message' => "Список книг получен",
            ],
        ]);
    }

    public function actionGetBooksPagination()
    {
        $get = Yii::$app->request->get();
        
        $model = new Book();
        $model->scenario='get';
        $model->load($get, '');

        
        if ($model->validate()) {
            $model->page -= 1;
    
            $query = new Query;
            $query = $query->select('*')
                ->from('file')
                ->leftJoin('user', 'file.user_id = user.id')
                ->rightJoin('book', 'file.id = book.file_id')
                ->where(['role_id' => 2]);
    
            $provider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => $model->count,
                    'page' => $model->page,
                ],
                // 'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
            ]);
            $result = [];
            $models = $provider->getModels();
            foreach ($models as $model) {
                $result[] = [
                    'id' => $model['id'],
                    'title' => $model['title'],
                    'autor' => $model['autor'],
                    'description' => $model['description'],
                    'file_url' => $model['file_url'],
                ];
            }
            return $this->asJson([
                'data' => [
                    'books' => [
                        $result,
                    ],
                    'code' => 200,
                    'message' => "Список книг получен",
                    'total_books' => $provider->totalCount,
                ],
            ]);
        } else {
            return $model->getErrors();
        }
    }
}
