<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Book".
 *
 * @property int $id
 * @property string $title
 * @property string $autor
 * @property string $description
 * @property int $file_url
 * @property int $is_public
 * @property int $user_id
 *
 * @property File $fileUrl
 * @property Progress[] $progresses
 * @property User $user
 */
class Book extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Book';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'autor', 'description', 'file_url', 'is_public', 'user_id'], 'required'],
            [['description'], 'string'],
            [['file_url', 'is_public', 'user_id'], 'integer'],
            [['title'], 'string', 'max' => 64],
            [['autor'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['file_url'], 'exist', 'skipOnError' => true, 'targetClass' => File::class, 'targetAttribute' => ['file_url' => 'id']],
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
            'file_url' => 'File Url',
            'is_public' => 'Is Public',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Gets query for [[FileUrl]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFileUrl()
    {
        return $this->hasOne(File::class, ['id' => 'file_url']);
    }

    /**
     * Gets query for [[Progresses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProgresses()
    {
        return $this->hasMany(Progress::class, ['book_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}
