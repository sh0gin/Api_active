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
            'only' => ['logout', 'uploadsbook', 'get-books-from-user', 'get-info-book-from-user', 'delete-book']
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
                $model_file = new File();
                $model_file->user_id = Yii::$app->user->id;
                $model_file->file_url = $model->upload($model->file_id);

                $model_file->save(false);
                $model->file_id = $model_file->id;
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
        $model->scenario = 'get';
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

    public function actionGetInfoBook()
    {
        $model = new Book();
        $model = $model->findOne(['id' => Yii::$app->request->get('id')]);
        if ($model) {
            $model_file = new File();
            $model_file = $model_file->findOne(['id' => $model->file_id]);

            $model_user = new User();
            $model_user = $model_user->findOne(['id' => $model_file->user_id]);

            if ($model_user->role_id == 2) {
                Yii::$app->response->statusCode = 200;

                return $this->asJson([
                    'data' => [
                        'id' => $model->id,
                        'title' => $model->title,
                        'author' => $model->autor,
                        'descriprion' => $model->description,
                        'file_url' => $model_file->file_url,
                    ],
                    'code' => 200,
                    'message' => "Информация о книге получена",
                ]);
            } else {
                Yii::$app->response->statusCode = 403;
            }
        } else {
            Yii::$app->response->statusCode = 404;
        }
    }

    public function actionGetBooksFromUser()
    {
        $id = Yii::$app->user->id;
        $model_file = new File();
        $model_file = $model_file->findAll(['user_id' => $id]);
        $id_file = [];
        foreach ($model_file as $model) {
            $id_file[] = $model->id;
        }

        $model_book = new Book();
        $model_book = $model_book->find()->where(['file_id' => $id_file])->all();
        $result = [];

        foreach ($model_book as $model) {
            $result[] = [
                'id' => $model->id,
                'title' => $model->title,
                'author' => $model->autor,
                'description' => $model->description,
                'file_url' => File::findOne(['id' => $model->file_id])->file_url,
            ];
        }

        return $this->asJson([
            'data' => [
                'books' => [
                    $result,
                ]
            ],
            'code' => 200,
            'message' => 'Список книг получен'
        ]);
    }

    public function actionGetInfoBookFromUser()
    {
        $id_book = Yii::$app->request->get()['id'];
        $id = Yii::$app->user->id;
        if ($id) {

            $model_book = Book::findOne($id_book);
            $model_file = File::findOne($model_book->file_id);

            if ($id) {
                return $this->asJson([
                    'data' => [
                        'book' => [
                            'id' => $model_book->id,
                            'title' => $model_book->title,
                            'autor' => $model_book->autor,
                            'description' => $model_book->description,
                            'file_url' => $model_file->file_url,
                        ]
                    ]
                ]);
            } else {
                Yii::$app->response->statusCode = 404;
            }
        } else {
            Yii::$app->response->statusCode = 403;
        }
    }

    public function actionDeleteBook()
    {
        if (Yii::$app->user->id) {
            $model_book = Book::findOne(Yii::$app->request->get()['id']);
            $model_file = File::findOne($model_book->file_id);
            $model_user = User::findOne(Yii::$app->user->id);
            if ($model_book) {
                if ($model_file->user_id == $model_user->id) {
                    $model_book->delete();
                    $model_file->delete();
                    Yii::$app->response->statusCode = 200;
                } else {
                    Yii::$app->response->statusCode = 403;
                }
            }
        } else {
            Yii::$app->response->statusCode = 403;
            return $this->asJson([
                "message" => "Login failed"
            ]);
        }
    }
}
