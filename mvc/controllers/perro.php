<?php

require_once 'core/mvc/J3Controller.php';

use J3\Core\Mvc\J3Controller;

/**
 * @layout perro
*/
class PerroController extends J3Controller {
   public $nombre = "Guao guao";

   /**
    * @returnType XML
    */
   public function gato() {
      echo "Estoy en gato!";
   }
}


?>
