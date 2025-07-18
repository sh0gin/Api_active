<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Parametr".
 *
 * @property int $id
 * @property string $font_family
 * @property int $font_size
 * @property string $text_color
 * @property string $background_color
 *
 * @property User[] $users
 */
class Parametr extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Parametr';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['font_family', 'font_size', 'text_color', 'background_color'], 'required'],
            [['font_size'], 'integer'],
            [['font_family'], 'string', 'max' => 255],
            ['background_color', 'match', 'pattern' => '/#[A-Z0-9]{6}$/'],
            ['text_color', 'match', 'pattern' => '/#[A-Z0-9]{6}$/'],
            [['text_color', 'background_color'], 'string', 'max' => 7],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'font_family' => 'Font Family',
            'font_size' => 'Font Size',
            'text_color' => 'Text Color',
            'background_color' => 'Background Color',
        ];
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['parametr_id' => 'id']);
    }

}
