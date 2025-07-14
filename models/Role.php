<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Role".
 *
 * @property int $id
 * @property int $role
 *
 * @property User[] $users
 */
class Role extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Role';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role'], 'required'],
            [['role'], 'integer'],
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

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['role' => 'id']);
    }

    public static function getRoleId($role) {
            return self::findOne(['role' => $role])->id;
    }

}
