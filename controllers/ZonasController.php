<?php

namespace app\controllers;

use yii\filters\Cors;

use app\models\Zona;
use app\models\ZonaDenominacion;

class ZonasController extends \yii\web\Controller
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

    public function actionAll()
    {
      $salida = ['errors'=>'','data'=>[]];

      \Yii::$app->response->format = 'json';

      $post = file_get_contents("php://input");
      $data = json_decode($post, true);

      $salida['data'] = Zona::getBusqueda($data);

      return $salida;
    }

    public function actionGetDenominaciones(){
      $salida = ['errors'=>'','data'=>[]];

      \Yii::$app->response->format = 'json';

      $post = file_get_contents("php://input");
      $data = json_decode($post, true);

      $salida['data'] = ZonaDenominacion::getAll();

      return $salida;
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

      $Z = new Zona();
      $Z->id            = $data['id'];
      $Z->nivel         = $data['nivel'];
      $Z->nombre        = $data['nombre'];
      $Z->final         = $data['final'];
      $Z->denominacion  = $data['denominacion'];

      if($data['root_id'] != NULL){
        $Z->id_root = $data['root_id'];
      }

      $salida['data'] = $Z->save();

      $salida['errors'] = $Z->errors;

      return $salida;
    }

}
