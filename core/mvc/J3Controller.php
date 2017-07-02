<?php
/**
 * J3 PHP Framework - core/mvc/J3Controller.php
 *
 * This file contains the base controller class.
 *
 * @author J3 Team
 *
 * @changelog
 *  1. 2017-07-01: Initial version
 */

namespace J3\Core\Mvc;

use J3\Core\J3Utils;

class J3Controller {

   protected $classAnnotations = array();

   public function __construct() {
      $this->classAnnotations = J3Utils::getClassAnnotations($this);
   }

   /**
    * Index method
    */
   public function index() {
      // Do nothing... Must be implemented in child classes
   }

   /**
    * Executes the specific method
    * @param $method
    */
   public function execute($method) {
      if (!method_exists($this, $method)) {
         echo "Metodo $method no existe!!!";
         return;
      }
      
      $this->$method();
      $ann = J3Utils::getMethodAnnotations($this, $method);

      print_r($ann);
   }


}

?>
