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

require_once 'J3View.php';

use J3\Core\J3Utils;
use J3\Core\Mvc\J3View;

class J3Controller {

   protected $classAnnotations = array();
   protected $classLayout = "default";
   protected $apiClass = false;
   protected $classAlias = null;

   public function __construct() {
      // get class annotations
      $this->classAnnotations = J3Utils::getClassAnnotations($this);

      //evaluate class annotations
      foreach ($this->classAnnotations as $key => $value) {
         if ($key === J3Utils::ANN_CLASS_LAYOUT) {
            $this->classLayout = $value;
         } else if ($key === J3Utils::ANN_CLASS_API) {
            $this->apiClass = true;
         } else if ($key === J3Utils::ANN_CLASS_ALIAS) {
            $this->classAlias = $value;
         }
      }
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

      // method variables
      $methodView = $method;
      $methodApi = false;
      $methodApiReturn = null;
      $methodLayout = $this->classLayout;

      // get method annotations
      $methodAnnotations = J3Utils::getMethodAnnotations($this, $method);

      // evaluate annotations
      foreach ($methodAnnotations as $key => $value) {
         if ($key === J3Utils::ANN_METHOD_VIEW) {
            $methodView = $value;
         } else if ($key === J3Utils::ANN_METHOD_LAYOUT) {
            $methodLayout = $value;
         } else if ($key === J3Utils::ANN_METHOD_API) {
            $methodApi = true;
         } else if ($key === J3Utils::ANN_METHOD_API_RETURN) {
            $methodApiReturn = $value;
         }
      }

      // run method
      $this->$method();

      // method is not api?
      if ($methodApi === false) {
         $view = new J3View($this, $methodLayout, $methodView);
         $view->render();
      } else {
         // TODO API
      }
   }

   public function getBaseName() {
      $name = get_class($this);
      $name = substr($name, 0, strlen($name)-strlen(J3Utils::SUF_CONTROLLER));
      $name = strtolower($name);

      return $name;
   }


}

?>
