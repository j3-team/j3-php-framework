<?php
/**
 * J3 PHP Framework - core/J3Utils.php
 *
 * This file contains utiles functions.
 *
 * @author J3 Team
 *
 * @changelog
 *  1. 2017-03-14: Initial version
 *  2. 2017-05-06: Rename class
 *  3. 2017-07-01: Add constans
 *  4. 2017-07-03: More constans
 *                 Include functions for API repsonses
 *  5. 2017-07-07: Constans for ini files
 *  6. 2017-07-09: Constans for DB annotations
 */

namespace J3\Core;


class J3Utils {

   // Constans: Directories and files
   const DIR_CFG             = 'cfg/';
   const DIR_CORE            = 'core/';
   const DIR_DOCS            = 'docs/';
   const DIR_EXTRAS          = 'extras/';
   const DIR_LOGS            = 'logs/';
   const DIR_MODULES         = 'modules/';
   const DIR_MVC             = 'mvc/';
   const DIR_MVC_CONTROLLERS = J3Utils::DIR_MVC . 'controllers/';
   const DIR_MVC_MODELS      = J3Utils::DIR_MVC . 'models/';
   const DIR_MVC_VIEWS       = J3Utils::DIR_MVC . 'views/';
   const DIR_MVC_LAYOUTS     = J3Utils::DIR_MVC . 'layouts/';
   const DIR_RESOURCES       = 'resources/';

   // Constans: INI Files
   const FILE_INI_RESOURCES  = J3Utils::DIR_CFG . 'resources.ini';
   const FILE_INI_APP        = J3Utils::DIR_CFG . 'app.ini';
   const FILE_INI_DB         = J3Utils::DIR_CFG . 'db.ini';
   const FILE_INI_MODULES    = J3Utils::DIR_CFG . 'modules.ini';

   // Constans: Suffixes
   const SUF_CONTROLLER      = 'Controller';

   // Constans: Class Annotations
   const ANN_CLASS_LAYOUT    = 'Layout';
   const ANN_CLASS_API       = 'Api';
   const ANN_CLASS_ALIAS     = 'Alias';

   // Constans: Method Annotations
   const ANN_METHOD_LAYOUT      = 'Layout';
   const ANN_METHOD_API         = 'Api';
   const ANN_METHOD_VIEW        = 'View';
   const ANN_METHOD_RETURN_TYPE = 'ReturnType';
   const ANN_METHOD_MIME_TYPE   = 'MimeType';

   // Constans: DB Model Annotations
   const ANN_DBMODEL_TABLE      = 'Table';
   const ANN_DBMODEL_PK         = 'PrimaryKey';

   // Constans: Default values
   const DEFAULT_LAYOUT        = 'j3default';
   const DEFAULT_CONTROLLER    = 'j3base';


   /* ---- UTILS METHODS ---- */

   /**
    * Converts Array to JSON plain.
    * @param  Array $input PHP Array.
    * @return String       JSON String.
    */
   static function raw_json_encode($input) {
   	return preg_replace_callback(
   			'/\\\\u([0-9a-zA-Z]{4})/',
   			function ($matches) {
   				return mb_convert_encoding(pack('H*',$matches[1]),'UTF-8','UTF-16');
   			},
   			json_encode($input)
   	);
   }

   /**
    * Retrieves variable name. Example, for var $name return "name".
    * @param  Reference  $var   Var reference.
    * @param  integer    $scope Scope.
    * @return String            Variale name.
    */
   static function getVariableName(&$var, $scope=0) {
      $old = $var;
      if (($key = array_search($var = 'unique'.rand().'value', !$scope ? $GLOBALS : $scope)) && $var = $old) {
         return $key;
      }
   }

   /**
    * Get and returns Class Annotations.
    * @param  String $class Class name.
    * @return Array         Annotations.
    */
   static function getClassAnnotations($class) {
      $classAnnotations = array();
      $r = new \ReflectionClass(get_class($class));
      $doc = $r->getDocComment();
      preg_match_all('#@(.*?)\n#s', $doc, $annotations);
      if (count($annotations[1]) > 0) {
         foreach ($annotations[1] as $an) {
            $parts = preg_split('/\s+/', $an);
            if (count($parts) == 1) {
               $classAnnotations[$parts[0]] = null;
            } else {
               $classAnnotations[$parts[0]] = $parts[1];
            }
         }
      }
      return $classAnnotations;
   }

   /**
    * Get and returns Method annotations.
    * @param  String $class  Class name.
    * @param  String $method Method name.
    * @return Array         Annotations.
    */
   static function getMethodAnnotations($class, $method) {
      $methodAnnotations = array();
      $r = new \ReflectionMethod(get_class($class), $method);
      $doc = $r->getDocComment();
      preg_match_all('#@(.*?)\n#s', $doc, $annotations);
      if (count($annotations[1]) > 0) {
         foreach ($annotations[1] as $an) {
            $parts = preg_split('/\s+/', $an);
            if (count($parts) == 1) {
               $methodAnnotations[$parts[0]] = null;
            } else {
               $methodAnnotations[$parts[0]] = $parts[1];
            }
         }
      }
      return $methodAnnotations;
   }


   /**
    * Download one file.
    * @param  String $file File path.
    * @param  String $type File mime-type
    * @return void
    */
   static function downloadFile($file, $type) {
      if (file_exists($file)) {
   		header('Content-Description: File Transfer');
   		header('Content-Type: ' . $type);
   		header('Content-Disposition: attachment; filename="' . basename($file) . '"');
   		header('Expires: 0');
   		header('Cache-Control: must-revalidate');
   		header('Pragma: public');
   		header('Content-Length: ' . filesize($file));
   		readfile($file);
   	}
   	exit(0);
   }

}

?>
