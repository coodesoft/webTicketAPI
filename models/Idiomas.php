<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "idiomas".
 *
 * @property int $id
 * @property string $nombre
 * @property string $iso
 * @property int $activo
 */
class Idiomas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'idiomas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['nombre'], 'string', 'max' => 191],
            [['iso'], 'string', 'max' => 3],
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
            'iso' => 'Iso',
            'activo' => 'Activo',
        ];
    }
}
