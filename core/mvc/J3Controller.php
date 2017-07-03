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
 *  2. 2017-07-03: Change messages language
 *                 Validate annotation values
 *
 */

namespace J3\Core\Mvc;

use J3\Core\J3Utils;
use J3\Core\Mvc\J3View;

class J3Controller {

   protected $classAnnotations = array();
   protected $classLayout = J3Utils::DEFAULT_LAYOUT;
   protected $apiClass = false;
   protected $classAlias = null;

   public function __construct() {
      // get class annotations
      $this->classAnnotations = J3Utils::getClassAnnotations($this);

      //evaluate class annotations
      foreach ($this->classAnnotations as $key => $value) {
         $withValue = true;
         if ($key === J3Utils::ANN_CLASS_LAYOUT) {
            $this->classLayout = $value;
         } else if ($key === J3Utils::ANN_CLASS_API) {
            $this->apiClass = true;
            $withValue = false;
         } else if ($key === J3Utils::ANN_CLASS_ALIAS) {
            $this->classAlias = $value;
         }

         if ($withValue === true && ($value === "" || !isset($value) || $value === null)) {
            J3View::warning("Annotation <strong>$key</strong> must have a value.");
         }
      }
   }


   /**
    * Executes the specific method
    * @param $method
    */
   public function execute($method) {
      if (!method_exists($this, $method)) {
         J3View::warning("Method <strong>$method</strong> not defined.");
         return;
      }

      // method variables
      $methodView = $method;
      $methodApi = $this->apiClass;
      $methodApiReturn = "RAW";
      $methodMimeType = null;
      $methodLayout = $this->classLayout;

      // get method annotations
      $methodAnnotations = J3Utils::getMethodAnnotations($this, $method);

      // evaluate annotations
      foreach ($methodAnnotations as $key => $value) {
         $withValue = true;
         if ($key === J3Utils::ANN_METHOD_VIEW) {
            $methodView = $value;
         } else if ($key === J3Utils::ANN_METHOD_LAYOUT) {
            $methodLayout = $value;
         } else if ($key === J3Utils::ANN_METHOD_API) {
            $methodApi = true;
            $withValue = false;
         } else if ($key === J3Utils::ANN_METHOD_RETURN_TYPE) {
            $methodApiReturn = $value;
         } else if ($key === J3Utils::ANN_METHOD_MIME_TYPE) {
            $methodMimeType = $value;
         }

         if ($withValue === true && ($value === "" || !isset($value) || $value === null)) {
            J3View::warning("Annotation <strong>$key</strong> must have a value.");
         }
      }

      // now, is time to run method

      // is method not api?
      if ($methodApi === false) {
         $this->$method();
         $view = new J3View($this, $methodLayout, $methodView);
         $view->render();
      } else { // method is api
         $returnOfMethod = $this->$method();
         if (!isset($returnOfMethod) || $returnOfMethod === null) {
            J3View::warning("Method <strong>$method</strong> must return a value.");
            // TODO evaluate if use exit instead warning
         } else {
            if ($methodApiReturn === 'JSON') {
               J3Utils::responseJSON($returnOfMethod);
            } else if ($methodApiReturn === 'XML') {
               // TODO implement XML
            } else if ($methodApiReturn === 'FILE') {
               // TODO implement file
            } else { // RAW
               // TODO implement RAW
            }

         }

      }
   }

   /**
    * Returns base name for this class
    * @return String
    */
   public function getBaseName() {
      $name = get_class($this);
      $name = substr($name, 0, strlen($name)-strlen(J3Utils::SUF_CONTROLLER));
      $name = strtolower($name);

      return $name;
   }


}

?>
