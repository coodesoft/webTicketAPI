<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipos_propiedad".
 *
 * @property int $id
 * @property string $nombre
 * @property int $activo
 * @property string $created_at
 * @property string $updated_at
 */
class TiposPropiedad extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tipos_propiedad';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['nombre'], 'string', 'max' => 100],
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
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
