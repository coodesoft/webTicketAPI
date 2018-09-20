<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_users".
 *
 * @property int $id
 * @property string $nombre
 * @property int $activo
 * @property string $created_at
 * @property string $updated_at
 */
class TipoUsers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tipo_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre', 'activo'], 'required'],
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
