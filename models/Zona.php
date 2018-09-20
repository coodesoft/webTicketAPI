<?php

namespace app\models;

use Yii;

use app\models\ZonaDenominacion;

class Zona{

  public $id           = '';
  public $nombre       = '';
  public $id_root      = '';
  public $activo       = true;
  public $nivel        = -1;
  public $final        = true;
  public $denominacion = 0;

  public $errors = '';

  public static function getBusqueda($busqueda){
    $condition = ['a.nivel' => $busqueda['nivel']];

    if($busqueda['root'] != -1){
      $condition['a.id_root'] = $busqueda['root'];
    }

    return (new \yii\db\Query)
              ->select('a.*, b.nombre AS nombre_root, c.nombre AS n_denom')
              ->from(self::getTableName().' AS a')
              ->leftJoin(self::getTableName().' AS b','b.id = a.id_root')
              ->leftJoin(ZonaDenominacion::getTableName().' AS c','c.id = a.denominacion')
              ->where($condition)
              ->All();

  }

  public function save(){
    $params = ['nombre'=>$this->nombre,'activo'=>$this->activo,'nivel'=>$this->nivel,'final'=>$this->final,'denominacion'=>$this->denominacion];

    if($this->nivel>1){ //la primera tabla no tiene el campo id_root
      $params['id_root'] = $this->id_root;
    }

    if ($this->id == -1){ // si es un registro nuevo
      return Yii::$app->db->createCommand()
              ->insert(self::getTableName(), $params)
              ->execute();
    }

    return Yii::$app->db->createCommand()
            ->update(self::getTableName(), $params,['id' => $this->id])
            ->execute();

  }


  public static function getTableName(){ return 'zona'; }
}
