<?php

namespace app\controllers;
use \Datetime;

use yii\filters\Cors;

use app\models\Propiedades;
use app\models\Archivos;
use app\models\Inmobiliaria;
use app\models\User;
use app\models\TipoOperacion;
use app\models\TiposPropiedad;
use app\models\Equipamientos;
use app\models\Ambientes;
use app\models\Servicios;
use app\models\Caracteristicas;
use app\models\PropiedadHasEquipamientos;
use app\models\PropiedadHasServicio;
use app\models\PropiedadHasAmbiente;

class PropiedadController extends \yii\web\Controller
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

    public function actionGetCaracteristicas(){
      $salida = ['errors'=>'','data'=>[]];

      \Yii::$app->response->format = 'json';

      $salida['data'] = Caracteristicas::getAll();

      return $salida;
    }

    public function actionGetAmbientes(){
      $salida = ['errors'=>'','data'=>[]];

      \Yii::$app->response->format = 'json';

      $salida['data'] = Ambientes::find()->asArray()->all();

      return $salida;
    }

    public function actionGetServicios(){
      $salida = ['errors'=>'','data'=>[]];

      \Yii::$app->response->format = 'json';

      $salida['data'] = Servicios::find()->asArray()->all();

      return $salida;
    }

    public function actionEquipamientos()
    {
      $salida = ['errors'=>'','data'=>[]];

      \Yii::$app->response->format = 'json';

      $salida['data'] = Equipamientos::find()->asArray()->all();

      return $salida;
    }

    public function actionSearch()
    {
      $salida = ['errors'=>'','data'=>[]];

      \Yii::$app->response->format = 'json';

      $post = file_get_contents("php://input");
      $data = json_decode($post, true);

      $terminos = [];
      $terminos['P.activo'] = 1;
      if(isset($data['zona']) && $data['zona'] != -1) {    $terminos['P.zona_id'] = $data['zona']; }
      if($data['tipoOperacion'] != -1) {$terminos['P.tipo_operacion_id'] = $data['tipoOperacion'];}
      if($data['tipoPropiedad'] != -1) {$terminos['P.tipo_propiedad_id'] = $data['tipoPropiedad'];}

      $salida['data'] = (new \yii\db\Query) //[Modificar] mover para el modelo
                          ->select('P.id, tipos_propiedad.nombre AS tipo_prop_nombre, moneda.simbolo AS moneda_simbolo, P.titulo, P.texto, P.inmobiliaria_id, P.propietario_id, P.localidad_id, P.tipo_operacion_id, P.tipo_propiedad_id, P.dormitorios, P.banios, P.capacidad, P.precio, P.created_at')
                          ->from('propiedades AS P')
                          ->innerJoin('moneda','moneda.id = P.moneda')
                          ->leftJoin('tipos_propiedad','tipos_propiedad.id = P.tipo_propiedad_id')
                          ->where($terminos)
                          ->orderBy('id DESC')
                          ->all();

      foreach ($salida['data'] as $k => $v) {
        $salida['data'][$k]['files']      = Archivos::getFiles($v['id']);

        $salida['data'][$k]['filesCount'] = count($salida['data'][$k]['files']);
        if (count($salida['data'][$k]['files'])==0){
          $salida['data'][$k]['files'][] = ['nombre' => '']; //[modificar]
        }
        $salida['data'][$k]['inmobiliaria'] = Inmobiliaria::findOne(['id' => $v['inmobiliaria_id']]);
      }

      return $salida;
    }

    public function actionGetInfo()
    {
      $salida = ['errors'=>'','data'=>[]];

      \Yii::$app->response->format = 'json';

      $post = file_get_contents("php://input");  //[Modificar] todo lo que sea funcionalidad mandarlo al modelo
      $data = json_decode($post, true);
      if(!$data){
        $salida['errors'] = 'params not found';
        return $salida;
      }

      $prop = Propiedades::getById($data['id']);
      $salida['data']               = $prop;
      $salida['data']['files']      = Archivos::getFiles($data['id']);
      $salida['data']['filesCount'] = count($salida['data']['files']); //[modificar]
      $salida['data']['localidad']  = Localidades::findOne(['id' => $salida['data'][0]['localidad_id']])['nombre'];
      $salida['data']['servicios']  = PropiedadHasServicio::getServicios($data['id']);
      $salida['data']['ambientes']  = PropiedadHasAmbiente::getAmbientes($data['id']);
      $salida['data']['carac_gral'] = Caracteristicas::getCaracteristicasFromPropiedad($prop);

      $equipo = PropiedadHasEquipamientos::find()->where(['propiedad_id' => $data['id']])->asArray()->all();
      foreach ($equipo as $k => $v) {
          $salida['data']['amenities'][] = Equipamientos::findOne(['id'=>$v['equipamiento_id']]);
      }

      if (count($salida['data']['files'])==0){
        $salida['data']['files'][] = ['nombre' => '']; //[modificar]
      }
      $salida['data']['inmobiliaria'] = Inmobiliaria::findOne(['id' => $salida['data'][0]['inmobiliaria_id']]);

      return $salida;
    }

    public function actionCreate()
    {
      $salida = ['errors'=>'','data'=>[]];

      \Yii::$app->response->format = 'json';

      $post = file_get_contents("php://input");
      $data = json_decode($post, true);
      if(!$data){
        $salida['errors'] = 'params not found';
        return $salida;
      }

      $fecha = new DateTime();

      $prop = new Propiedades();
      $prop->titulo              = $data['titulo'];
      $prop->texto               = $data['texto'];

      $prop->localidad_id        = $data['localidad'];
      $prop->barrio_id           = $data['barrio'];
      $prop->direccion           = $data['direccion'];
      $prop->tipo_propiedad_id   = $data['tipo_propiedad_id'];
      $prop->tipo_operacion_id   = $data['tipo_operacion_id'];
      $prop->antiguedad_id       = 0;
      $prop->capacidad           = $data['capacidad']; //
      $prop->dormitorios         = $data['dormitorios'];
      $prop->banios              = $data['banios'];
      $prop->ambientes           = $data['ambientes_c'];
      $prop->calle1              = $data['calle1'];
      $prop->calle2              = $data['calle2'];
      $prop->dpto                = $data['dpto'];
      $prop->precio              = $data['precio'];
      $prop->moneda              = $data['moneda'];
      $prop->cochera             = $data['cochera'];
      $prop->piscina             = 0;
      $prop->mascota             = 0;
      $prop->seguridad           = 0;
      $prop->lavarropas          = 0;
      $prop->quincho             = 0;
      $prop->superficie_cubierta = $data['superficie_cubierta'];
      $prop->superficie_total    = $data['superficie_total'];
      $prop->disposicion         = $data['disposicion'];
      $prop->latitud             = 1;
      $prop->longitud            = 1;
      $prop->valor_dia           = $data['valor_dia'];
      $prop->valor_semana        = $data['valor_semana'];
      $prop->valor_mes           = $data['valor_mes'];
      $prop->disponible          = 1;
      $prop->activo              = true;
      $prop->destacado           = $data['destacado'];
      $prop->expensas            = $data['expensas'];

      //actualizacion de campos de caracteristicas generales
      $caArr = Caracteristicas::getAll();     //[modificar] //en algun momento este tipo de caracteristicas se tendrian que almacenar de otra manera
      foreach ($data['carac_gral'] as $k => $v){
        if($data['carac_gral'] != null){
          $prop[$caArr[$k]['f']] = $v;
        }
      }

      //parametros que no debería ingresar el usuario pero bue, para algo esta el token
      $prop->inmobiliaria_id = 1;//$data['inmobiliaria_id']; //[modificar]
      $prop->propietario_id  = $data['propietario_id'];

      //parametros no ingresados por el usuario
      $prop->created_at = date('Y-n-j h:i:s');
      $prop->updated_at = date('Y-n-j h:i:s');

      $salida['data'] = ['success' => $prop->save(false), 'id' => $prop->id]; //agregar transacciones
      // actualizamos el contaador de propiedades del usuario
      if($salida['data']['success']){
        User::propCountPlus($prop->propietario_id);
      }
      //nuevos registros de equipamientos
      foreach ($data['equipamiento'] as $k => $v) {
        if($v){
          $e = new PropiedadHasEquipamientos();
          $e->propiedad_id = $prop->id;
          $e->equipamiento_id = $k;
          $e->save(false);
        }
      }
      //nuevos registros de servicios
      foreach ($data['servicios'] as $k => $v){
        if($v){
          $e = new PropiedadHasServicio();
          $e->id_propiedad = $prop->id;
          $e->id_servicio  = $k;
          $e->save(false);
        }
      }
      //nuevos registros de Ambientes
      foreach ($data['ambientes'] as $k => $v){
        if($v){
          $e = new PropiedadHasAmbiente();
          $e->id_propiedad = $prop->id;
          $e->id_ambiente  = $k;
          $e->save(false);
        }
      }

      foreach ($data['imgs'] as $k => $v) {
        $nombre = $fecha->getTimestamp().$v['filename'];
        file_put_contents(dirname(__FILE__).'/../../img/'.$nombre, base64_decode($v['value']));
        $arch = new Archivos();
        $arch->nombre        = 'img/'.$nombre;
        $arch->ruta          = 'img/'; //[modificar]
        $arch->tipo          = '';
        $arch->tamanio       = '';
        $arch->propiedad_id = $prop->id;
        $arch->save(false);
      }

      return $salida;
    }

    public function actionGetSearchConfig()
    {
      $salida = ['errors'=>'','data'=>[]];

      \Yii::$app->response->format = 'json';

      $post = file_get_contents("php://input");
      $data = json_decode($post, true);

      if(!$data){
        $salida['errors'] = 'params not found';
        return $salida;
      }

      $salida['data']['ubicaciones_busqueda'] = Propiedades::getBMPopZonas( ['cant'=>10]);
      $salida['data']['tipo_operacion']       = Propiedades::getBMPopTipoOP(['cant'=>10]);
      $salida['data']['tipo_propiedad']       = Propiedades::getBMPopTipoPR(['cant'=>10]);
      $salida['data']['cant_banios']          = Propiedades::getBMPopCantBN(['cant'=>10]);
      $salida['data']['ambientes']            = Propiedades::getBMPopAmbien(['cant'=>10]);
      $salida['data']['dormitorios']          = Propiedades::getBMPopDormit(['cant'=>10]);
      $salida['data']['cocheras']             = Propiedades::getBMPopCocher(['cant'=>10]);
      $salida['data']['tipos_ambientes']      = Propiedades::getBMPopTAmbie(['cant'=>40]);
      $salida['data']['servicios']            = Propiedades::getBMPopTServi(['cant'=>40]);
      $salida['data']['generales']            = Propiedades::getBMPopCGrals();
      $salida['data']['tanunciante']          = Propiedades::getBMPopTAnunc();

      return $salida;
    }

}
