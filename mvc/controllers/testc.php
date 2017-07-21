<?php

/**
 * @Layout test1
 */
class TestcController extends J3BaseController {
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
      $la_variable = "Local Defined Var";
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

   /**
    * Prueba de RAW API
    * @Api
    * @ReturnType RAW
    */
   public function six() {

      return 258;
   }


   public function seven() {
      $tabla = J3DB::table('mensaje');
      
   }
}


?>
