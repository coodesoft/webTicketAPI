<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property int $tipo_user_id
 * @property string $remember_token
 * @property string $token
 * @property string $created_at
 * @property string $updated_at
 *
 * @property UserHasPermissions[] $userHasPermissions
 * @property Permissions[] $permissions
 * @property UserHasRoles[] $userHasRoles
 * @property Roles[] $roles
 */
class User extends \yii\db\ActiveRecord
{
    private $c;

    public static function getAll(){ // [Modificar] por que entrega demasiados datos
      return (new \yii\db\Query)
                ->select('*, tipo_users.nombre AS tipo_user_name')
                ->from(self::tableName())
                ->innerJoin('tipo_users','tipo_users.id = users.tipo_user_id')
                ->All();
    }

    public static function propCountPlus($id){
      $U = self::findOne(['id' => $id]);
      $U->cant_prop = $U->cant_prop+1;
      return $U->save(false);
    }

    public static function getProfile($id){
      $sal = (new \yii\db\Query)
                ->select('U.name, U.id, U.surname, U.tel, U.email, U.celular, U.profile_img, U.id_inmobiliaria, U.cant_prop, U.created_at, tipo_users.nombre AS tipo_user_name')
                ->from(self::tableName().' AS U')
                ->innerJoin('tipo_users','tipo_users.id = U.tipo_user_id')
                ->where(['U.id'=>$id])
                ->All()[0];

      $sal['inmobiliaria'] = (new \yii\db\Query)
                ->select('*')
                ->from('inmobiliaria')
                ->where(['id'=>$sal['id_inmobiliaria']])
                ->All();
      return $sal;
    }

    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'email', 'password', 'tipo_user_id', 'token'], 'required'],
            [['tipo_user_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'email', 'password'], 'string', 'max' => 191],
            [['remember_token'], 'string', 'max' => 100],
            [['token'], 'string', 'max' => 128],
            [['email'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'tipo_user_id' => 'Tipo User ID',
            'remember_token' => 'Remember Token',
            'token' => 'Token',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function login($pass){
      if ($this->password == null)
        return false;

      if (password_verify($pass,$this->password)){
        $this->newToken();
        return true;
      }

      return false;
    }

    private function newToken(){
      $this->token_datetime = date('Y-m-d H-i-s');
      $this->token = password_hash($this->email.$this->token_datetime, PASSWORD_DEFAULT); //'Y3J1enN1aXphOmNydXpzdWl6YQ==';

      $userM = User::findOne(['token'=>$this->token]);
      if ($userM != null){
        $this->c ++;
        return $this->newResetPassToken();
      }

      if ($this->c > 10){
        $this->errors .= ' error 2-u';
        return false;
      }

      if ($this->save(false)){
        return true;
      }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserHasPermissions()
    {
        return $this->hasMany(UserHasPermissions::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermissions()
    {
        return $this->hasMany(Permissions::className(), ['id' => 'permission_id'])->viaTable('user_has_permissions', ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserHasRoles()
    {
        return $this->hasMany(UserHasRoles::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoles()
    {
        return $this->hasMany(Roles::className(), ['id' => 'role_id'])->viaTable('user_has_roles', ['user_id' => 'id']);
    }
}
