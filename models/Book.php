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
 * @property string $file
 * @property int $count
 * @property int $page
 * @property int $is_public
 * @property int $user_id
 */
class Book extends \yii\db\ActiveRecord
{
    public $checkExtensionByMimeType = false;

    public $count;
    public $page;
    public $file;
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
            [['title'], 'required', 'on' => 'book'],
            [['description', 'autor'], 'string', 'on' => 'book'],
            
            [['title'], 'required', 'on' => 'edit'],
            [['description', 'autor'], 'string', 'on' => 'edit'],

            // [['file'], 'file', 'extensions' => ['html'], 'skipOnEmpty' => false, 'maxSize' => 1024*512, 'on' => 'book'],
            [['count', 'page'], 'required', 'on' => 'get'],
            [['count', 'page'], 'integer', 'on' => 'get'],
            ['is_public', 'boolean', 'on' => 'admin', 'message' => 'Must have value FALSE or TRUE'],
            [['file'], 'file', 'extensions' => ['html'], 'skipOnEmpty' => false, 'maxSize' => 1024 * 512, 'on' => 'book'],

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
            'is_public' => 'Is Public',
            'user_id' => 'User ID',
        ];
    }
}
