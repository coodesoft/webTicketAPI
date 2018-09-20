<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "propiedad_has_ambiente".
 *
 * @property int $id
 * @property int $id_propiedad
 * @property int $id_ambiente
 */
class PropiedadHasAmbiente extends \yii\db\ActiveRecord
{
    public static function getAmbientes($propiedad){
      return (new \yii\db\Query)
                ->select('id_ambiente, nombre')
                ->from(self::tableName().' AS a')
                ->innerJoin('ambientes','ambientes.id = a.id_ambiente')
                ->where(['a.id_propiedad'   => $propiedad,
                         'ambientes.activo' => 1])
                ->All();
    }

    public static function tableName()
    {
        return 'propiedad_has_ambiente';
    }

    public function rules()
    {
        return [
            [['id_propiedad', 'id_ambiente'], 'required'],
            [['id_propiedad', 'id_ambiente'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_propiedad' => 'Id Propiedad',
            'id_ambiente' => 'Id Ambiente',
        ];
    }
}
