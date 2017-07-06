<?php

/**
 * @Layout test1
 */
class TestcController extends J3GenericController {
   public $name = "Test Controller 4";

   /**
    *
    */
   public function one() {
   }

   /**
    * @Layout test2
    * @View three
    */
   public function two() {
      $el_perrito = "Scooby Doo";
   }

   /**
    * Prueba de JSON API
    * @Api
    * @ReturnType JSON
    */
   public function four() {
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

   /**
    * Prueba de JSON API
    * @Api
    * @ReturnType XML
    */
   public function five() {
      $arr = array(
         "@root" => "elemRaiz",
         "@attributes" => array(
            "attr1" => "Prueba atributo"
         ),
         "uno" => "Valor 1",
         "dos" => array(
            array(
               "tres" => "Valor 3",
               "cuatro" => "Valor 4"
            ),
            array(
               "tres" => "Valor 3.1",
               "cuatro" => array('@cdata' => 'Valor 4.1 con tags tipo <test>Hola</test>'),
            )
         )
      );

      return $arr;
   }
}


?>
