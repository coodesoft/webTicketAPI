<?php

namespace app\models;

use Yii;

class Propiedades extends \yii\db\ActiveRecord
{
    // Funcion que devuelve las zonas con mayor cantidad de propiedades
    public static function getBMPopZonas($p){
      return (new \yii\db\Query)
                  ->select('Z.id, Z.nombre AS name, COUNT(Z.id) AS cant')
                  ->from(self::tableName().' AS P')
                  ->innerJoin('zona AS Z','Z.id = P.zona_id')
                  ->groupBy(['Z.id'])
                  ->orderBy(['cant' => 'SORT_DESC'])
                  ->limit($p['cant'])
                  ->all();
    }

    // Función que devuelve los tipos de operación con mayor cantidad de propiedades
    public static function getBMPopTipoOP($p){
      return (new \yii\db\Query)
                  ->select('T.id, T.nombre AS name, COUNT(T.id) AS cant')
                  ->from(self::tableName().' AS P')
                  ->innerJoin('tipo_operacion AS T','T.id = P.tipo_operacion_id')
                  ->groupBy(['P.tipo_operacion_id'])
                  ->orderBy(['cant' => 'SORT_DESC'])
                  ->limit($p['cant'])
                  ->all();
    }

    //función que devuelve los tipos de propiedades más populares
    public static function getBMPopTipoPR($p){
      return (new \yii\db\Query)
                  ->select('T.id, T.nombre AS name, COUNT(T.id) AS cant')
                  ->from(self::tableName().' AS P')
                  ->innerJoin('tipos_propiedad AS T','T.id = P.tipo_propiedad_id')
                  ->groupBy(['P.tipo_propiedad_id'])
                  ->orderBy(['cant' => 'SORT_DESC'])
                  ->limit($p['cant'])
                  ->all();
    }

    //función que devuelve las cantidades de baños más populares
    public static function getBMPopCantBN($p){
      return (new \yii\db\Query)
                  ->select('P.banios AS name, COUNT(P.banios) AS cant')
                  ->from(self::tableName().' AS P')
                  ->groupBy(['P.banios'])
                  ->orderBy(['cant' => 'SORT_DESC'])
                  ->limit($p['cant'])
                  ->all();
    }

    //función que devuelve los cantidades de ambientes mas populares [modificar] como que hay mucha repeticón de código en agunas funciones, no?
    public static function getBMPopAmbien($p){
      return (new \yii\db\Query)
                  ->select('P.ambientes AS name, COUNT(P.ambientes) AS cant')
                  ->from(self::tableName().' AS P')
                  ->groupBy(['P.ambientes'])
                  ->orderBy(['cant' => 'SORT_DESC'])
                  ->limit($p['cant'])
                  ->all();
    }

    //funcion que devuelve las cantidades de dormitorios más populares
    public static function getBMPopDormit($p){
      return (new \yii\db\Query)
                  ->select('P.dormitorios AS name, COUNT(P.dormitorios) AS cant')
                  ->from(self::tableName().' AS P')
                  ->groupBy(['P.dormitorios'])
                  ->orderBy(['cant' => 'SORT_DESC'])
                  ->limit($p['cant'])
                  ->all();
    }

    //función que devuelve las cantidades de cocheras más populares
    public static function getBMPopCocher($p){
      return (new \yii\db\Query)
                  ->select('P.cochera AS name, COUNT(P.cochera) AS cant')
                  ->from(self::tableName().' AS P')
                  ->groupBy(['P.cochera'])
                  ->orderBy(['cant' => 'SORT_DESC'])
                  ->limit($p['cant'])
                  ->all();
    }

    //función que devuelve los tipos de ambientes más populares
    public static function getBMPopTAmbie($p){
      return (new \yii\db\Query)
                  ->select('EA.id, EA.nombre AS name, COUNT(A.id_ambiente) AS cant')
                  ->from('propiedad_has_ambiente AS A')
                  ->innerJoin('ambientes AS EA','EA.id = A.id_ambiente')
                  ->groupBy(['A.id_ambiente'])
                  ->orderBy(['cant' => 'SORT_DESC'])
                  ->limit($p['cant'])
                  ->all();
    }

    //función que devuelve los tipos de servicios más populares
    public static function getBMPopTServi($p){
      return (new \yii\db\Query)
                  ->select('S.id_servicio AS id, EA.nombre AS name, COUNT(S.id_servicio) AS cant')
                  ->from('propiedad_has_servicio AS S')
                  ->innerJoin('servicios AS EA','EA.id = S.id_servicio')
                  ->groupBy(['S.id_servicio'])
                  ->orderBy(['cant' => 'SORT_DESC'])
                  ->limit($p['cant'])
                  ->all();
    }

    //función que devuelve las cantidades por características generales
    public static function getBMPopCGrals(){
      return [
        ['name'=>'Piscina','cant'=>5000],
        ['name'=>'Solarium','cant'=>5000],
        ['name'=>'Parrilla','cant'=>5000],
        ['name'=>'Gimnasio','cant'=>5000],
        ['name'=>'Apto crédito','cant'=>5000],
        ['name'=>'Acceso para personas con movilidad reducida','cant'=>5000],
        ['name'=>'Acepta seguro de caución','cant'=>5000],
        ['name'=>'Apto profesional','cant'=>5000],
        ['name'=>'Cancha de fútbol','cant'=>5000],
        ['name'=>'Cancha de tenis','cant'=>5000],
      ];
    }

    //función que devuelve las cantidades por tipos de usuarios más populares
    public static function getBMPopTAnunc(){
      return (new \yii\db\Query)
                  ->select('U.tipo_user_id AS id, TU.nombre AS name, COUNT(U.tipo_user_id) AS cant')
                  ->from('propiedades AS P')
                  ->innerJoin('users AS U','U.id = P.propietario_id')
                  ->innerJoin('tipo_users AS TU','TU.id = U.tipo_user_id')
                  ->groupBy(['U.tipo_user_id'])
                  ->orderBy(['cant' => 'SORT_DESC'])
                  ->all();
    }

    public static function getById($id){
        //se busca la propiedad
        $prop = (new \yii\db\Query) //[Modificar]
                          ->select('P.*, tipo_operacion.nombre AS nombre_operacion, moneda.simbolo AS moneda_simbolo')
                          ->from('propiedades AS P')
                          ->innerJoin('tipo_operacion','tipo_operacion.id = P.tipo_operacion_id')
                          ->innerJoin('moneda','moneda.id = P.moneda')
                          ->where(['P.id' => $id, 'P.activo' => 1])
                          ->all();

        //se buscan los datos de contacto
        $user = (new \yii\db\Query)
                    ->select('id, name, surname, tel, celular, tipo_user_id ')
                    ->from('users')
                    ->where(['id' => $prop[0]['propietario_id']])
                    ->all();

        $prop[0]['user'] = $user[0];
        return $prop;
    }

    public static function tableName(){ return 'propiedades'; }

    public function rules()
    {
        return [
            [['titulo', 'texto', 'localidad_id', 'direccion'], 'required'],
            [['texto'], 'string'],
            [['inmobiliaria_id', 'propietario_id', 'localidad_id', 'barrio_id', 'tipo_propiedad_id', 'tipo_operacion_id', 'antiguedad_id'], 'integer'],
            [['superficie'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['titulo', 'direccion'], 'string', 'max' => 200],
            [['capacidad', 'dormitorios', 'banios', 'ambientes'], 'string', 'max' => 3],
            [['cochera', 'piscina', 'mascota', 'seguridad', 'lavarropas', 'quincho', 'disponible', 'activo', 'destacado'], 'string', 'max' => 1],
            [['latitud', 'longitud'], 'string', 'max' => 50],
            [['valor_dia', 'valor_semana', 'valor_mes'], 'string', 'max' => 100],
        ];
    }

    public function attributeLabels()
    {
        return [];
    }

    public function getArchivos()
    {
        return $this->hasMany(Archivos::className(), ['propiedad_id' => 'id']);
    }
}
