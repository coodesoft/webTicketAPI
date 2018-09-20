<?php
namespace app\models;

class Caracteristicas{
//todas estas cosas "feas" que hay dentro de este archivo se deben a que hay que pensar
//como acomadarlas en un esquema de diseño de base de datos
  public static function getCaracteristicasFromPropiedad($prop){
    $salida = [];
    $caract = self::getAll();
    foreach ($caract as $c => $f) {
      foreach ($prop[0] as $k => $v) {

        if ($f['f'] == $k && $v != null){ //si es igual quiere decir que este elemento se corresponde a un campo "referenciado" entre las caracteristicas
          switch($f['type']){
            case 'sl':
              if ($v != null){
                $salida[] = ['n'=>$f['nombre'], 'v'=>$f['o'][$v]['nombre'], 't'=>$f['type']];
              }
            break;

            case 'tx':
              $salida[] = ['n'=>$f['nombre'], 'v'=>$v, 't'=>$f['type']];
            break;

            case 'ck':
              $salida[] = ['n'=>$f['nombre'], 'v'=>$v, 't'=>$f['type']];
            break;
          }
        }
      }
    }
    return $salida;
  }

  public static function getAll(){
    return [ //o es un arreglo de multiples opciones que puede usar el campo y se usa con type = sl, f representa al campo de la tabla propiedades al que se hace referencia
      ['id'=>'0', 'f' => 'etapa', 'nombre'=>'Etapa','type'=>'sl','o'=>[
        ['id'=> '0', 'nombre' => 'En pozo'],
        ['id'=> '1', 'nombre' => 'En construccion'],
        ['id'=> '2', 'nombre' => 'Terminado']
        ]],
      ['id'=>'1', 'f' => 'tipo_techo', 'nombre'=>'Tipo techo','type'=>'sl','o'=>[
        ['id'=> '0', 'nombre' => 'Chapa'],
        ['id'=> '1', 'nombre' => 'Loza'],
        ['id'=> '2', 'nombre' => 'Pizarra'],
        ['id'=> '3', 'nombre' => 'Teja']
        ]],
      ['id'=>'2', 'f' => 'largo_terreno', 'nombre'=>'Largo terreno','type'=>'tx'],
      ['id'=>'3', 'f' => 'frente', 'nombre'=>'Frente','type'=>'tx'],
      ['id'=>'4', 'f' => 'luminosidad', 'nombre'=>'Luminosidad','type'=>'sl','o'=>[
        ['id'=> '0', 'nombre' => 'Muy luminoso'],
        ['id'=> '1', 'nombre' => 'Luminoso'],
        ['id'=> '2', 'nombre' => 'Poco luminoso']
        ]],
      ['id'=>'5', 'f' => 'estado_inmueble', 'nombre'=>'Estado inmueble','type'=>'sl','o'=>[
        ['id'=> '0', 'nombre' => 'Reciclado'],
        ['id'=> '1', 'nombre' => 'Excelente'],
        ['id'=> '2', 'nombre' => 'Muy bueno'],
        ['id'=> '3', 'nombre' => 'Bueno'],
        ['id'=> '4', 'nombre' => 'Regular'],
        ['id'=> '5', 'nombre' => 'A refaccionar'],
        ]],
      ['id'=>'6', 'f' => 'cobertura_cochera', 'nombre'=>'Cobertura cochera','type'=>'sl','o'=>[
        ['id'=> '0', 'nombre' => 'Cubierta'],
        ['id'=> '1', 'nombre' => 'Semi cubierta'],
        ['id'=> '2', 'nombre' => 'Descubierta'],
        ]],
      ['id'=>'7', 'f' => 'cantidad_plantas', 'nombre'=>'Cantidad plantas','type'=>'tx'],
      ['id'=>'8', 'f' => 'camas', 'nombre'=>'Camas','type'=>'tx'],
      ['id'=>'9', 'f' => 'parque_infantil', 'nombre'=>'Parque infantil','type'=>'ck'],
      ['id'=>'10', 'f' => 'pension', 'nombre'=>'Pensión','type'=>'sl','o'=>[
        ['id'=> '0', 'nombre' => 'Pension completa'],
        ['id'=> '1', 'nombre' => 'Media pension'],
        ['id'=> '2', 'nombre' => 'Desayuno'],
        ]],
      ['id'=>'11', 'f' => 'entorno', 'nombre'=>'Alrededor del alojamiento','type'=>'sl','o'=>[
        ['id'=> '0', 'nombre' => 'Ciudad'],
        ['id'=> '1', 'nombre' => 'Espacio rural'],
        ['id'=> '2', 'nombre' => 'Montañas'],
        ['id'=> '2', 'nombre' => 'Playa'],
        ]],
      ['id'=>'12', 'f' => 'limpieza_final', 'nombre'=>'Limpieza final','type'=>'sl','o'=>[
        ['id'=> '0', 'nombre' => 'Incluida'],
        ['id'=> '1', 'nombre' => 'No incluida'],
        ]],
      ['id'=>'13', 'f' => 'parque_infantil', 'nombre'=>'Parque infantil','type'=>'ck'],
      ['id'=>'14', 'f' => 'gastos', 'nombre'=>'Gastos','type'=>'ck'],
      ['id'=>'15', 'f' => 'cancha_tenis', 'nombre'=>'Cancha de tenis','type'=>'ck'],
      ['id'=>'16', 'f' => 'gimnasio', 'nombre'=>'Gimnasio','type'=>'ck'],
      ['id'=>'17', 'f' => 'hidromasaje', 'nombre'=>'Hidromasaje','type'=>'ck'],
      ['id'=>'18', 'f' => 'parrilla', 'nombre'=>'Parrilla','type'=>'ck'],
      ['id'=>'19', 'f' => 'solarium', 'nombre'=>'Solarium','type'=>'ck'],
      ['id'=>'20', 'f' => 'mov_reducida', 'nombre'=>'Movilidad reducida','type'=>'ck']
    ];

  }
}
 ?>
