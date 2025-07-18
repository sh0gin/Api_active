<?php

namespace app\controllers;

use app\models\Book;
use app\models\File;
use app\models\Parametr;
use app\models\Progress;
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
            'only' => ['logout', 'uploadsbook', 'get-books-from-user', 'get-info-book-from-user', 'delete-book', 'edit-book', "save-progress", 'get-progress', 'get-books-progress', 'get-books', 'get-info-book', 'set-settings', 'change-visibility'],
            'optional' => ['get-books', 'get-info-book'],
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
                $role_id = User::findOne(Yii::$app->user->id)->role_id;
                if ($role_id == 2) {
                    $model->is_public = 1;
                }
                $model_file->file_url = $model->upload($model->file_id);

                $model_file->save(false);
                $model->file_id = $model_file->id;
                $model->save(false);
                Yii::$app->response->statusCode = 201;
                return $this->asJson([
                    'data' => [
                        'book' => [
                            'id' => $model->id,
                            'title' => $model->title,
                            'author' => $model->autor,
                            'description' => $model->description,
                            'file_url' => $_SERVER['HTTP_HOST'] . '/models/uploads' . $model->file_id,
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
            ->rightJoin('book', 'file.id = book.file_id');

        $admin = false;
        if (Yii::$app->user->id) {
            if (User::findOne(Yii::$app->user->id)->role_id == 2) {
                $admin = true;
            } else {
                $query = $query->where(['user_id' => Yii::$app->user->id]);
            }
        } else {
            $query = $query->where(['role_id' => 2, 'is_public' => true]);
        }

        $result = [];
        $post = Yii::$app->request->post();

        if ($post) {
            $model = new Book();
            $model->scenario = 'get';
            $model->load(['count' => $post['count'], 'page' => $post['page']], '');

            if ($model->validate()) {
                $model->page -= 1;
                $provider = new ActiveDataProvider([
                    'query' => $query,
                    'pagination' => [
                        'pageSize' => $model->count,
                        'page' => $model->page,
                    ],
                    // 'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
                ]);
            }
            $query = $provider->getModels();
        } else {
            $query = $query->createCommand()->queryAll();
        }

        Yii::$app->response->statusCode = 200;

        // var_dump($query); die;
        if ($admin) {
            foreach ($query as $model) {
                $result[] = [
                    'id' => $model['id'],
                    'title' => $model['title'],
                    'autor' => $model['autor'],
                    'description' => $model['description'],
                    'file_url' => $_SERVER['HTTP_HOST'] . '/models/uploads' . $model['file_url'],
                    'is_public' => $model['is_public'],
                ];
            }
        } else {

            foreach ($query as $model) {
                $result[] = [
                    'id' => $model['id'],
                    'title' => $model['title'],
                    'autor' => $model['autor'],
                    'description' => $model['description'],
                    'file_url' => $_SERVER['HTTP_HOST'] . '/models/uploads' . $model['file_url'],
                ];
            }
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

    // public function actionGetBooksPagination() 

    // {
    // $post = Yii::$app->request->post();
    // $model = new Book();
    // $model->scenario = 'get';
    // $model->load(['count' => $post['count'], 'page' => $post['page']], '');


    // if ($model->validate()) {
    //     $model->page -= 1;

    //     $query = new Query;
    //     $query = $query->select('*')
    //         ->from('file')
    //         ->leftJoin('user', 'file.user_id = user.id')
    //         ->rightJoin('book', 'file.id = book.file_id')
    //         ->where(['role_id' => 2, 'is_public' => true]);

    //     $provider = new ActiveDataProvider([
    //         'query' => $query,
    //         'pagination' => [
    //             'pageSize' => $model->count,
    //             'page' => $model->page,
    //         ],
    //         // 'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
    //     ]);
    //     $result = [];
    //     $models = $provider->getModels();
    //     foreach ($models as $model) {
    //         $result[] = [
    //             'id' => $model['id'],
    //             'title' => $model['title'],
    //             'autor' => $model['autor'],
    //             'description' => $model['description'],
    //             'file_url' => $_SERVER['HTTP_HOST'] . '/models/uploads' . $model['file_url'],
    //         ];
    //     }
    //     return $this->asJson([
    //         'data' => [
    //             'books' => [
    //                 $result,
    //             ],
    //             'code' => 200,
    //             'message' => "Список книг получен",
    //             'total_books' => $provider->totalCount,
    //         ],
    //     ]);
    // } else {
    //     return $model->getErrors();
    // }
    // }

    // public function actionGetBooksFromUser($id)
    // {
    //     $model_file = new File();
    //     $model_file = $model_file->findAll(['user_id' => $id]);
    //     $id_file = [];
    //     foreach ($model_file as $model) {
    //         $id_file[] = $model->id;
    //     }

    //     $model_book = new Book();
    //     $model_book = $model_book->find()->where(['file_id' => $id_file])->all();
    //     $result = [];

    //     foreach ($model_book as $model) {
    //         $result[] = [
    //             'id' => $model->id,
    //             'title' => $model->title,
    //             'author' => $model->autor,
    //             'description' => $model->description,
    //             'file_url' => $_SERVER['HTTP_HOST'] . '/models/uploads' . File::findOne(['id' => $model->file_id])->file_url,
    //         ];
    //     }

    //     return $this->asJson([
    //         'data' => [
    //             'books' => [
    //                 $result,
    //             ]
    //         ],
    //         'code' => 200,
    //         'message' => 'Список книг получен'
    //     ]);
    // }



    public function actionGetInfoBook($id)
    {
        $model = new Book();
        $model = $model->findOne($id);

        if ($model) {
            $model_file = new File();
            $model_file = $model_file->findOne(['id' => $model->file_id]);

            $model_user = new User();
            $model_user = $model_user->findOne(['id' => $model_file->user_id]);
            // var_dump($model_user->role_id == 2);
            if (($model_user->role_id == 2 && $model->is_public == 1) || (Yii::$app->user->id == $model_user->id)) {
                Yii::$app->response->statusCode = 200;

                return $this->asJson([
                    'data' => [
                        'id' => $model->id,
                        'title' => $model->title,
                        'author' => $model->autor,
                        'descriprion' => $model->description,
                        'file_url' => $_SERVER['HTTP_HOST'] . '/models/uploads' . $model_file->file_url,
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

    // public function actionGetInfoBookFromUser()
    // {
    //     $id_book = Yii::$app->request->get()['id'];
    //     $id = Yii::$app->user->id;

    //     $model_book = Book::findOne($id_book);
    //     $model_file = File::findOne($model_book->file_id);

    //     if ($id) {
    //         return $this->asJson([
    //             'data' => [
    //                 'book' => [
    //                     'id' => $model_book->id,
    //                     'title' => $model_book->title,
    //                     'autor' => $model_book->autor,
    //                     'description' => $model_book->description,
    //                     'file_url' => $_SERVER['HTTP_HOST'] . '/models/uploads' . $model_file->file_url,
    //                 ]
    //             ]
    //         ]);
    //     } else {
    //         Yii::$app->response->statusCode = 404;
    //     }
    // }

    public function actionDeleteBook($id)
    {
        $model_book = Book::findOne($id);
        $model_file = File::findOne($model_book->file_id);
        $model_user = User::findOne(Yii::$app->user->id);
        if ($model_book) {
            if ($model_file->user_id == $model_user->id) {
                $model_book->delete();
                $model_file->delete();
                Yii::$app->response->statusCode = 200;
            } else {
                Yii::$app->response->statusCode = 402;
            }
        }
    }

    public function actionEditBook($id)
    {

        $book = Book::findOne($id);
        if ($book) {
            $file = File::findOne($book->file_id);
            $user = User::findOne($file->user_id);
            if ($user->id == Yii::$app->user->id) {
                $post = Yii::$app->request->post();

                $book->title = $post['title'];
                $book->autor = $post['author'];
                $book->description = $post['description'];
                $book->save();
                Yii::$app->response->statusCode = 200;
                return $this->asJson([
                    'data' => [
                        'book' => [
                            'id' => $book->id,
                            'title' => $book->title,
                            'author' => $book->autor,
                            'description' => $book->description,
                            'file_url' => $_SERVER['HTTP_HOST'] . '/models/uploads' .  $file->description,
                        ],
                        'code' => 200,
                        'message' => "Информация о книге обновлена"
                    ]
                ]);
            } else {
                Yii::$app->response->statusCode = 402;
            }
        } else {
            Yii::$app->response->statusCode = 404;
        }
    }

    public function actionSaveProgress($id)
    {
        $id_active_user = Yii::$app->user->id;
        $book = Book::findOne($id);

        if ($book) {

            $file = File::findOne($book->file_id);

            if ($book->is_public || $id_active_user == $file->user_id) {

                $progress = new Progress();
                $book = Progress::findOne(['user_id' => $id_active_user, 'book_id' => $id]);

                if ($book) {
                    $progress = $book;
                    $progress->progress = Yii::$app->request->post()['progress'];
                } else {
                    $progress->load(['book_id' => $id, 'user_id' => $id_active_user, 'progress' => Yii::$app->request->post()['progress']], '');
                }
                if ($progress->validate()) {
                    if ($progress->save()) {
                        Yii::$app->response->statusCode = 200;

                        return $this->asJson([
                            'data' => [
                                'book_id' => $progress->id,
                                'progress' => $progress->progress,
                                'code' => 200,
                                'message' => "Прогресс чтения сохранён",
                            ]
                        ]);
                    } else {
                        Yii::$app->response->statusCode = 422;

                        $valid = $progress->getErrors();
                        return $this->asJson([
                            'error' => [
                                'code' => 422,
                                'message' => 'Validation error',
                                'errors' => [
                                    $valid,
                                ]
                            ]
                        ]);
                    };
                } else {
                    $valid = $progress->getErrors();
                    return $this->asJson([
                        'errors' => [
                            'code' => 422,
                            'message' => 'Validate Error',
                            'errors' => $valid,

                        ]
                    ]);
                }
            } else {
                Yii::$app->response->statusCode = 403;
            }
        } else {
            Yii::$app->response->statusCode = 404;
        }
    }

    public function actionGetProgress($id)
    {
        $book = Book::findOne($id);
        // var_dump($id, Yii::$app->user->id); die;
        if ($book) {
            $file = File::findOne($book->file_id);
            if ($file->user_id == Yii::$app->user->id) {
                $progress = Progress::findOne(['book_id' => $id, 'user_id' => Yii::$app->user->id]);

                Yii::$app->response->statusCode = 200;
                return $this->asJson([
                    'data' => [
                        'book_id' => $book->id,
                        'progress' => $progress ? $progress->progress : 0,
                        'code' => 200,
                        'message' => "Прогресс чтения получен",
                    ]
                ]);
            } else {
                Yii::$app->response->statusCode = 402;
            }
        } else {
            Yii::$app->response->statusCode = 404;
        }
    }

    public function actionGetBooksProgress()
    {
        $provider = new ActiveDataProvider([
            'query' => Progress::find()->where(['user_id' => Yii::$app->user->id]),
            'pagination' => [],
            // 'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);
        $result = [];

        foreach ($provider->getModels() as $model) {
            $model_book = Book::findOne($model->book_id);
            $model_file = File::findOne($model_book->file_id);
            $result[] = [
                'id' => $model_book->id,
                'title' => $model_book->title,
                'author' => $model_book->autor,
                'description' => $model_book->description,
                'file_url' => $model_file->file_url,
            ];
        }
        return $this->asJson([
            'data' => [
                'books' => [
                    $result,
                ],
                'code' => 200,
                'message' => "Список книг, которые читает пользователь, получен",
                'total_books' => $provider->totalCount,
            ]
        ]);
    }

    public function actionSetSettings()
    {
        $model = new Parametr();
        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post(), '');
            if ($model->save()) {
                $user = User::findOne(Yii::$app->user->id);
                $user->parametr_id = $model->id;
                $user->save();
                Yii::$app->response->statusCode = 200;
                return $this->asJson([
                    'data' => [
                        'settings' => [
                            'font_family' => $model->font_family,
                            'font_size' => $model->font_size,
                            'text_color' => $model->text_color,
                            'background_color' => $model->background_color,
                        ],
                        'code' => 200,
                        'message' => 'Настрайки чтения сохранены',
                    ]
                ]);
            } else {
                $valid = $model->getErrors();
                Yii::$app->response->statusCode = 422;
                return $this->asJson([
                    'error' => [
                        'code' => 422,
                        'message' => 'Validation error',
                        'errors' => [
                            $valid,
                        ]
                    ]
                ]);
            }
        } else {
            $model = $model::findOne(User::findOne(Yii::$app->user->id)->parametr_id);
            return $this->asJson([
                'data' => [
                    'settings' => [
                        'font_family' => $model->font_family,
                        'font_size' => $model->font_size,
                        'text_color' => $model->text_color,
                        'background_color' => $model->background_color,
                    ],
                    'code' => 200,
                    'message' => 'Настрайки чтения получены',
                ]
            ]);
        }
    }

    public function actionChangeVisibility($id)
    {
        $user = User::findOne(Yii::$app->user->id);
        if ($user->role_id == 2) {
            if (Yii::$app->request->post()) {
                $book = Book::findOne($id);
                $book->scenario = 'admin';
                if ($book) {
                    $book->is_public = strtolower(Yii::$app->request->post()['is_public']);
                    if ($book->is_public == 'true') {
                        $book->is_public = 1;
                    } else if ($book->is_public == 'false') {
                        $book->is_public = 0;
                    }
                    if ($book->save()) {
                        return $this->asJson([
                            'data' => [
                                'book' => [
                                    'id' => $book->id,
                                    'is_public' => $book->is_public,
                                ],
                                'code' => 200,
                                'message' => 'Доступность книги изменена'
                            ]
                        ]);
                    } else {
                        $valid = $book->getErrors();
                        Yii::$app->response->statusCode = 422;
                        return $this->asJson([
                            'errors' => [
                                'code' => 422,
                                'message' => "Validation error",
                                'errors' => [
                                    $valid,
                                ]
                            ],
                        ]);
                    };
                } else {
                    Yii::$app->response->statusCode = 404;
                }
            }
        } else {
            Yii::$app->response->statusCode = 402;
        }


        return [$id, Yii::$app->user->id, Yii::$app->request->post()];
    }
}
