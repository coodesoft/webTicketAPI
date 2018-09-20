<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inmobiliaria".
 *
 * @property int $id
 * @property string $nombre
 * @property string $logo
 */
class Inmobiliaria extends \yii\db\ActiveRecord
{
    public static function getAll(){
      return (new \yii\db\Query)
                ->select('*')
                ->from(self::tableName())
                ->All();
    }

    public static function getById($id){
      return (new \yii\db\Query)
                ->select('*')
                ->from(self::tableName())
                ->where(['id' => $id])
                ->All();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'inmobiliaria';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre', 'logo'], 'required'],
            [['nombre', 'logo'], 'string', 'max' => 191],
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
            'logo' => 'Logo',
        ];
    }
}
