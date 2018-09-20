<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "servicios".
 *
 * @property int $id
 * @property string $nombre
 * @property int $activo
 */
class Servicios extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'servicios';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre', 'activo'], 'required'],
            [['nombre'], 'string', 'max' => 200],
            [['activo'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'activo' => 'Activo',
        ];
    }
}
