<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "archivos".
 *
 * @property int $id
 * @property string $nombre
 * @property string $ruta
 * @property string $tipo
 * @property string $tamanio
 * @property int $propiedad_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Propiedades $propiedad
 */
class Archivos extends \yii\db\ActiveRecord
{
    public static function getFiles($propiedad){
      return (new \yii\db\Query)
                ->select('id, nombre, ruta, tipo, tamanio')
                ->from(self::tableName())
                ->All();
    }


    public static function tableName()
    {
        return 'archivos';
    }

    public function rules()
    {
        return [
            [['nombre', 'ruta', 'tipo', 'tamanio', 'propiedad_id'], 'required'],
            [['propiedad_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['nombre', 'ruta', 'tipo', 'tamanio'], 'string', 'max' => 191],
            [['propiedad_id'], 'exist', 'skipOnError' => true, 'targetClass' => Propiedades::className(), 'targetAttribute' => ['propiedad_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'ruta' => 'Ruta',
            'tipo' => 'Tipo',
            'tamanio' => 'Tamanio',
            'propiedad_id' => 'Propiedad ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getPropiedad()
    {
        return $this->hasOne(Propiedades::className(), ['id' => 'propiedad_id']);
    }
}
