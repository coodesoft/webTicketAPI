<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "traducciones".
 *
 * @property int $id
 * @property string $idioma_iso
 * @property int $propiedad_id
 * @property int $parent_id
 * @property string $nombre
 * @property string $titulo
 * @property string $resumen
 * @property string $texto
 * @property string $otro
 */
class Traducciones extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'traducciones';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idioma_iso'], 'required'],
            [['propiedad_id', 'parent_id'], 'integer'],
            [['texto', 'otro'], 'string'],
            [['idioma_iso'], 'string', 'max' => 3],
            [['nombre', 'titulo', 'resumen'], 'string', 'max' => 191],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idioma_iso' => 'Idioma Iso',
            'propiedad_id' => 'Propiedad ID',
            'parent_id' => 'Parent ID',
            'nombre' => 'Nombre',
            'titulo' => 'Titulo',
            'resumen' => 'Resumen',
            'texto' => 'Texto',
            'otro' => 'Otro',
        ];
    }
}
