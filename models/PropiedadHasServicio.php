<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "propiedad_has_servicio".
 *
 * @property int $id
 * @property int $id_propiedad
 * @property int $id_servicio
 */
class PropiedadHasServicio extends \yii\db\ActiveRecord
{

    public static function getServicios($propiedad){
      return (new \yii\db\Query)
                ->select('id_servicio, nombre')
                ->from(self::tableName())
                ->innerJoin('servicios','servicios.id = propiedad_has_servicio.id_servicio')
                ->where(['propiedad_has_servicio.id_propiedad' => $propiedad,
                         'servicios.activo'                    => 1])
                ->All();
    }

    public static function tableName()
    {
        return 'propiedad_has_servicio';
    }

    public function rules()
    {
        return [
            [['id_propiedad', 'id_servicio'], 'required'],
            [['id_propiedad', 'id_servicio'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_propiedad' => 'Id Propiedad',
            'id_servicio' => 'Id Servicio',
        ];
    }
}
