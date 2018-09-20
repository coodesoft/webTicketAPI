<?php

namespace app\models;

use Yii;

class ZonaDenominacion{

  public static function getAll(){
    return (new \yii\db\Query)
              ->select('*')
              ->from(self::getTableName())
              ->All();
  }

  public static function getTableName(){ return 'zona_denominacion'; }
}
 ?>
