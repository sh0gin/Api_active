<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "File".
 *
 * @property int $id
 * @property int $book_id
 * @property string $file_url
 * @property string $data_uploads
 *
 * @property Book[] $books
 */
class File extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'File';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file_url'], 'required'],
            [['book_id'], 'required', 'on' => 'basic'],
            [['file_url'], 'file', 'extensions' => ['html'], 'skipOnEmpty' => false, 'maxSize' => 1024 * 512, 'on' => 'book'],

            // [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Book::class, 'targetAttribute' => ['book_id' => 'id']],
            [['file_url'], 'string', 'max' => 255, 'on' => 'basic'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file_url' => 'File Url',
            'data_uploads' => 'Data Uploads',
        ];
    }

    /**
     * Gets query for [[Books]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBooks()
    {
        return $this->hasMany(Book::class, ['file_url' => 'id']);
    }

    public function upload($file)
    {
        $path = Yii::$app->security->generateRandomstring() . ".{$file[0]->extension}";
        $file[0]->saveAs(__DIR__ . '/uploads/' . $path);
        return $path;
    }
}
