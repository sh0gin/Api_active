<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "File".
 *
 * @property int $id
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
            [['file_url', 'data_uploads'], 'required'],
            [['data_uploads'], 'safe'],
            [['file_url'], 'string', 'max' => 255],
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

}
