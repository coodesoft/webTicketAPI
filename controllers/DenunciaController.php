<?php

namespace app\controllers;

use yii\filters\Cors;

use app\models\Denuncia;

class DenunciaController extends \yii\web\Controller
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

    public function actionAll(){
      $salida = ['errors'=>'','data'=>[]];

      \Yii::$app->response->format = 'json';

      $salida['data'] = Denuncia::getAll();
      return $salida;
    }

    public function actionNueva()
    {
      $salida = ['errors'=>'','data'=>[]];

      \Yii::$app->response->format = 'json';

      $post = file_get_contents("php://input");
      $data = json_decode($post, true);

      if(!$data){
        $salida['errors'] = 'params not found';
        return $salida;
      }

      $d               = new Denuncia();
      $d->fecha        = date('Y-n-j h:i:s');
      $d->id_propiedad = $data['propiedad'];
      $d->id_usuario   = $data['denunciante'];
      $d->descripcion  = $data['descripcion'];
      $d->motivo       = $data['motivo'];

      $salida['data']['success'] = $d->save();

      return $salida;
    }

}
