<?php

/**
 * @Layout test
 */
class PerroController extends J3GenericController {
   public $nombre = "Guao guao";

   /**
    *
    */
   public function gato() {
   }

   /**
    * @Layout dos
    * @View oveja
    */
   public function loro() {
   }

   /**
    * Prueba de JSON API
    * @Api
    * @ReturnType JSON
    */
   public function test() {
      $arr = array(
         "uno" => "Valor 1",
         "dos" => array(
            array(
               "tres" => "Valor 3",
               "cuatro" => "Valor 4"
            ),
            array(
               "tres" => "Valor 3.1",
               "cuatro" => "Valor 4.1"
            )
         )
      );

      return $arr;
   }
}


?>
