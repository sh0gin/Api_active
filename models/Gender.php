<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "gender".
 *
 * @property int $id
 * @property string $role
 */
class Gender extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gender';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role'], 'required'],
            [['role'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role' => 'Role',
        ];
    }

    public static function getGender($gen)
    {   
        if ($gen == 1) {
            return 1;
        } else if ($gen == 2) {
            return 2;
        } else {
            return NULL;
        }
    }
}
