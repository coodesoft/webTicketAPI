<?php

namespace app\controllers;

use yii\filters\Cors;
use \Datetime;

use app\models\User;
use app\models\TipoUsers;
use app\models\Inmobiliaria;

class UserController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function behaviors()
    {
        return array_merge([
            'cors' => [
                'class' => Cors::className(),

                #common rules
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['POST','GET'],
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Allow-Credentials' => null,
                    'Access-Control-Max-Age' => 86400,
                    'Access-Control-Expose-Headers' => [],
                ]
            ],
        ], parent::behaviors());
    }

    public function actionCreate(){
      $salida = ['errors'=>'','data'=>[]];

      \Yii::$app->response->format = 'json';

      $post = file_get_contents("php://input");
      $data = json_decode($post, true);

      if(!$data){
        $salida['errors'] = 'params not found';
        return $salida;
      }

      $fecha = new DateTime();

      $U = new User();
      $U->email            = $data['email'];
      $U->enabled          = false;
      $U->name             = '';
      $U->surname          = '';
      $U->activation_token = password_hash($fecha->getTimestamp().$data['email'], PASSWORD_DEFAULT);
      $U->password         = password_hash($data['pass'], PASSWORD_DEFAULT);
      $U->tipo_user_id     = $data['tipoUser'];
      $U->tel              = $data['telFijo'];
      $U->celular          = $data['telefono'];

      $U->created_at       = date('Y-n-j h:i:s');
      $U->updated_at       = date('Y-n-j h:i:s');
      $U->profile_img      = 'img/default.png';
      $U->con_foto         = false;

      $salida['data']['success'] = $U->save(false);

    /*  \Yii::$app->mail->compose('confirm_user',['link' => ''])
        ->setFrom([\Yii::$app->params['adminEmail'] => 'creoprop'])
        ->setTo($data->email)
        ->setSubject('[creoprop] Confirmar usuario' )
        ->send();*/

      return $salida;
    }

    public function actionProfile(){
      $salida = ['errors'=>'','data'=>[]];

      \Yii::$app->response->format = 'json';

      $post = file_get_contents("php://input");
      $data = json_decode($post, true);

      if(!$data){
        $salida['errors'] = 'params not found';
        return $salida;
      }

      if(!$data['id']){
        $salida['errors'] = '?';
        return $salida;
      }

      $salida['data'] = User::getProfile($data['id']);

      return $salida;
    }

    public function actionEditProfile(){
      $salida = ['errors'=>'','data'=>[]];

      \Yii::$app->response->format = 'json';

      $post = file_get_contents("php://input");
      $data = json_decode($post, true);

      if(!$data){
        $salida['errors'] = 'params not found';
        return $salida;
      }

      $fecha = new DateTime();

      $U = User::findOne(['id' => $data['user_id']]);

      $U->updated_at = date('Y-n-j h:i:s');
      if($data['nombre']   != ''){ $U->name    = $data['nombre'];   }
      if($data['apellido'] != ''){ $U->surname = $data['apellido']; }
      if($data['celular']  != ''){ $U->celular = $data['celular']; }

      if($data['img_data'] != []){
        $nombre = $fecha->getTimestamp().$data['img_data'][0]['filename'];
        file_put_contents(dirname(__FILE__).'/../../img/'.$nombre, base64_decode($data['img_data'][0]['value']));
        $U->con_foto    = true;
        $U->profile_img = 'img/'.$nombre;
      }

      $salida['data']['success'] = $U->save(false);

      return $salida;
    }

    public function actionLogin(){
      $salida = ['errors'=>'','data'=>[]];

      \Yii::$app->response->format = 'json';

      $post = file_get_contents("php://input");
      $data = json_decode($post, true);

      if(!$data){
        $salida['errors'] = 'params not found';
        return $salida;
      }

      if($data['email'] == ''){
        $salida['errors'] = 'user not found';
        return $salida;
      }

      $U = User::findOne(['email' => $data['email']]);

      if ($U == null){ //si el usuario no existe
        $salida['errors'] = 'user not found';
        return $salida;
      }

      if ($U->login($data['pass'])){  // [modificar] tendria que hacerse en el modelo con el conswtructor de consultas
        $salida['data']['email']           = $U->email;
        $salida['data']['id']              = $U->id;
        $salida['data']['name']            = $U->name;
        $salida['data']['surname']         = $U->surname;
        $salida['data']['tipo_user_id']    = $U->tipo_user_id;
        $salida['data']['tel']             = $U->tel;
        $salida['data']['dni']             = $U->dni;
        $salida['data']['celular']         = $U->celular;
        $salida['data']['cuit']            = $U->cuit;
        $salida['data']['token']           = $U->token;
        $salida['data']['profile_img']     = $U->profile_img;
        $salida['data']['created_at']      = $U->created_at;
        $salida['data']['cant_prop']       = $U->cant_prop;

        $salida['data']['inmobiliaria'] = Inmobiliaria::getById($U->id_inmobiliaria);
      } else {
        $salida['errors'] = 'bad login';
      }

      return $salida;
    }

    public function actionLogout(){

    }

    public function actionGetUsers(){ // [Modificar] por nombre poco intuitivo
      $salida = ['errors'=>'','data'=>[]];

      \Yii::$app->response->format = 'json';

      $post = file_get_contents("php://input");
      $data = json_decode($post, true);

      $salida['data'] = TipoUsers::find(['activo' => 1])->orderBy('id')->asArray()->all();

      return $salida;
    }

    public function actionGetAll(){
      $salida = ['errors'=>'','data'=>[]];

      \Yii::$app->response->format = 'json';

      $post = file_get_contents("php://input");
      $data = json_decode($post, true);

      $salida['data'] = User::getAll();

      return $salida;
    }
}
