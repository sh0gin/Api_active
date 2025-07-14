<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "User".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property int $age
 * @property int $gender
 * @property string $password
 * @property int $parametr_id
 * @property int $role_id
 * @property string $token
 *
 * @property Book[] $books
 * @property Parametr $parametr
 * @property Progress[] $progresses
 * @property Role $role0
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'User';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'age', 'password'], 'required', 'on' => 'reg'],
            [['password', 'email'], 'required' ,'on' => 'auth'],

            [['gender'], 'integer', 'on' => 'reg'],
            [['age'], 'integer', 'on' => 'reg'],

            [['age'], 'compare', 'compareValue' => 2, 'operator' => '>=', 'type' => 'number', 'on' => 'reg'],
            [['age'], 'compare', 'compareValue' => 150, 'operator' => '<=', 'type' => 'number', 'on' => 'reg'],


            [['name'], 'match', 'pattern' => '/^([A-Z]|[А-Я]).+/', 'on' => 'reg'],
            [['name', 'email'], 'string', 'on' => 'reg'],
            [['email'], 'email', 'on' => 'reg'],
            ['email', 'unique', 'on' => 'reg'],
            ['password', 'match', 'pattern' =>'/(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z])[0-9a-zA-Z!@#$%^&*]{4}/', 'on' => 'reg'],
            
            // [['gender'], 'lenght' => [1,2]],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'age' => 'Age',
            'gender' => 'Gender',
            'password' => 'Password',
            'parametr_id' => 'Parametr ID',
            'role' => 'Role',
            'token' => 'Token',
        ];
    }

    /**
     * Gets query for [[Books]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBooks()
    {
        return $this->hasMany(Book::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Parametr]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParametr()
    {
        return $this->hasOne(Parametr::class, ['id' => 'parametr_id']);
    }

    /**
     * Gets query for [[Progresses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProgresses()
    {
        return $this->hasMany(Progress::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Role0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRole0()
    {
        return $this->hasOne(Role::class, ['id' => 'role']);
    }

     public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['token' => $token]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        // return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        // return $this->authKey === $authKey;
    }

    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function getIsAdmin() {
        return $this->role_id == Role::GetRoleId('admin');
    }

    
}
