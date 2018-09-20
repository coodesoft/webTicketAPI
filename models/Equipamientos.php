<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "equipamientos".
 *
 * @property int $id
 * @property string $nombre
 * @property int $activo
 * @property string $created_at
 * @property string $updated_at
 */
class Equipamientos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'equipamientos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['nombre'], 'string', 'max' => 191],
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
