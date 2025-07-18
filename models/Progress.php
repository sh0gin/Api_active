<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Progress".
 *
 * @property int $id
 * @property int $book_id
 * @property int $user_id
 * @property float $progress
 *
 * @property Book $book
 * @property User $user
 */
class Progress extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Progress';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['book_id', 'user_id', 'progress'], 'required'],
            [['book_id', 'user_id'], 'integer'],
            [['progress'], 'compare', 'compareValue' => 0, 'operator' => '>=', 'type' => 'number'],
            [['progress'], 'compare', 'compareValue' => 100, 'operator' => '<=', 'type' => 'number'],
            [['progress'], 'number'],
            [['book_id'], 'exist', 'skipOnError' => true, 'targetClass' => Book::class, 'targetAttribute' => ['book_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'book_id' => 'Book ID',
            'user_id' => 'User ID',
            'progress' => 'Progress',
        ];
    }

    /**
     * Gets query for [[Book]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBook()
    {
        return $this->hasOne(Book::class, ['id' => 'book_id']);
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
