<?php

namespace app\controllers;

use yii\filters\Cors;

use app\models\Propiedades;

class GralController extends \yii\web\Controller
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
    
    public function actionGetInfo()
    {
      $salida = ['errors'=>'','data'=>[]];

      \Yii::$app->response->format = 'json';

      $post = file_get_contents("php://input");
      $data = json_decode($post);

      $salida['data']['cantPropiedades'] = count(Propiedades::find()->asArray()->all());

      return $salida;
    }

}
