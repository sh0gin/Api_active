<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "book".
 *
 * @property int $id
 * @property string $title
 * @property string $autor
 * @property string $description
 * @property int $file_id
 * @property int $count
 * @property int $page
 * @property int $is_public
 * @property int $user_id
 */
class Book extends \yii\db\ActiveRecord
{
    public $count;
    public $page;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'book';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'file_id'], 'required', 'on' => 'book'],
            [['description', 'autor'], 'string', 'on' => 'book'],
            [['file_id'], 'file', 'extensions' => ['html'], 'skipOnEmpty' => false, 'maxSize' => 512000, 'on' => 'book'],
            [['count', 'page'], 'required', 'on' => 'get'],
            [['count', 'page'], 'integer', 'on' => 'get'],
            ['is_public', 'boolean', 'on' => 'admin', 'message' => 'Must have value FALSE or TRUE'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'autor' => 'Autor',
            'description' => 'Description',
            'file_id' => 'File ID',
            'is_public' => 'Is Public',
            'user_id' => 'User ID',
        ];
    }

    public function upload($file) {
        $path = Yii::$app->security->generateRandomstring() . ".{$file[0]->extension}";
        $file[0]->saveAs(__DIR__ . '/uploads/' . $path);
        return $path;
    }

}
