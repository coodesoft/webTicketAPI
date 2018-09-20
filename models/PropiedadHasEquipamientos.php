<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "propiedad_has_equipamientos".
 *
 * @property int $id
 * @property int $propiedad_id
 * @property int $equipamiento_id
 * @property string $created_at
 * @property string $updated_at
 */
class PropiedadHasEquipamientos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'propiedad_has_equipamientos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['propiedad_id', 'equipamiento_id'], 'required'],
            [['propiedad_id', 'equipamiento_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'propiedad_id' => 'Propiedad ID',
            'equipamiento_id' => 'Equipamiento ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
