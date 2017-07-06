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
 *  3. 2017-07-05: Create method at runtime to obtain its locally defined variables
 */

namespace J3\Core\Mvc;

use J3\Core\J3Utils;
use J3\Core\Mvc\J3View;
use J3\Core\Modules\J3ModuleLoader;

//test module load
J3ModuleLoader::loadExtra('Array2XML-0.8');

class J3Controller {

   protected $classAnnotations = array();
   protected $classLayout = J3Utils::DEFAULT_LAYOUT;
   protected $apiClass = false;
   protected $classAlias = null;

   public function __call($method, $args) {
        if (isset($this->$method)) {
            $func = $this->$method;
            return call_user_func_array($func, $args);
        }
    }

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
         // Modify and execute method
         $this->modifyMyMethod($method);
         $method = "j3_new_$method";
         $methodLocalVariables = $this->$method();

         $view = new J3View($this, $methodLayout, $methodView, $methodLocalVariables);
         $view->render();
      } else { // method is api
         $returnOfMethod = $this->$method();
         if (!isset($returnOfMethod) || $returnOfMethod === null) {
            J3View::warning("Method <strong>$method</strong> must return a value.");
            // TODO evaluate if use exit instead warning
         } else {
            if ($methodApiReturn === 'JSON') {
               J3Controller::responseJSON($returnOfMethod);
            } else if ($methodApiReturn === 'XML') {
               J3Controller::responseXML($returnOfMethod);
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


   /* RESPONSE METHODS */

   static function responseJSON($array, $stay = FALSE) {
		$jsonString = J3Utils::raw_json_encode($array);

		header("HTTP/1.1 200 OK");
		header("Content-type: application/json; charset=utf-8");

		echo $jsonString;

		if ($stay == FALSE) {
			exit(0);
		}
	}

   static function responseXML($array, $stay = FALSE) {
      $root = "root";
      if (isset($array["@root"])) {
         $root = $array["@root"];
         unset($array["@root"]);
      }

      $xml = \Array2XML::createXML($root, $array);

		header("HTTP/1.1 200 OK");
		header("Content-type: application/xml; charset=utf-8");

		echo $xml->saveXML();

		if ($stay == FALSE) {
			exit(0);
		}
	}

   /**
    * Modify source code of given method and create a new one with return variables.
    * @param  String $methodName Method name
    * @return void
    */
   private function modifyMyMethod($methodName) {
      // Get method properties
      $method = new \ReflectionMethod(get_class($this), $methodName);
      $filename = $method->getFileName();
      $start_line = $method->getStartLine();
      $end_line = $method->getEndLine();
      $length = $end_line - $start_line;

      // Obtain method source
      $source = file($filename);
      $body = implode("", array_slice($source, $start_line, $length));

      // Modify method source adding return line at the end
      $body = substr($body, 0,strrpos($body, '}'));
      $body = "$body return get_defined_vars();";

      //Create new function on me
      $newFunction = create_function('', $body);
      $methodName = "j3_new_$methodName";
      $this->$methodName = $newFunction;
   }
}

?>
