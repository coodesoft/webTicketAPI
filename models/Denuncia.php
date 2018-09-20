<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "denuncia".
 *
 * @property int $id
 * @property int $id_propiedad
 * @property int $id_usuario
 * @property string $descripcion
 * @property string $fecha
 * @property int $estado
 */
class Denuncia extends \yii\db\ActiveRecord
{

  public static function getAll(){
    return (new \yii\db\Query)
              ->select('*, motivo_denuncia.nombre AS motivo_text')
              ->from(self::tableName())
              ->innerJoin('motivo_denuncia','motivo_denuncia.id = denuncia.motivo')
              ->All();
  }

  public static function tableName() {  return 'denuncia'; }

  public function rules()
    {
        return [
            [['id_propiedad', 'id_usuario', 'descripcion', 'fecha'], 'required'],
            [['id_propiedad', 'id_usuario', 'estado'], 'integer'],
            [['fecha'], 'safe'],
            [['descripcion'], 'string', 'max' => 250],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_propiedad' => 'Id Propiedad',
            'id_usuario' => 'Id Usuario',
            'descripcion' => 'Descripcion',
            'fecha' => 'Fecha',
            'estado' => 'Estado',
        ];
    }
}
