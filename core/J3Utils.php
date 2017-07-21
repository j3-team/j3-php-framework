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
 *  7. 2017-07-20: Modules coding
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
   const DIR_CORE_CFG        = J3Utils::DIR_CORE . 'cfg/';
   const DIR_CORE_MODULES    = J3Utils::DIR_CORE . 'modules/';
   const DIR_CORE_MOD_BASE   = J3Utils::DIR_CORE_MODULES . 'base/';
   const DIR_CORE_MVC        = J3Utils::DIR_CORE . 'mvc/';
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
   const FILE_INI_MOD        = 'mod.ini';

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
   const ANN_DBMODEL_DATABASE   = 'Database';

   // Constans: Default values
   const DEFAULT_LAYOUT        = 'j3default';
   const DEFAULT_CONTROLLER    = 'j3base';

   // Constans: Module types
   const MOD_TYPE_API          = 'api';
   const MOD_TYPE_CLI          = 'cli';
   const MOD_TYPE_DATABASE     = 'database';
   const MOD_TYPE_FILE         = 'file';
   const MOD_TYPE_I18N         = 'i18n';
   const MOD_TYPE_LOGGING      = 'logging';
   const MOD_TYPE_MAIL         = 'mail';
   const MOD_TYPE_REQUEST      = 'request';
   const MOD_TYPE_SECURITY     = 'security';
   const MOD_TYPE_SERVICE      = 'service';
   const MOD_TYPE_UI           = 'ui';
   const MOD_TYPE_WIZARD       = 'wizard';

   // Constants: Module INI sections
   const MOD_INI_SECTION_MODULE  = 'module';
   const MOD_INI_SECTION_CUSTOM  = 'custom';
   const MOD_INI_KEY_ENABLED     = 'enabled';
   const MOD_INI_KEY_LOAD        = 'load';

   // Constants: Module keys (INI files). 1 => required
   const KEYS_MOD_API = array(
      // TODO define keys
   );
   const KEYS_MOD_CLI = array(
      // TODO define keys
   );
   const KEYS_MOD_DATABASE = array(
      'connection_file' => 1,
      'connection_class' => 1,
      'model_file' => 0,
      'model_class' => 0
   );
   const KEYS_MOD_FILE = array(
      // TODO define keys
   );
   const KEYS_MOD_I18N = array(
      // TODO define keys
   );
   const KEYS_MOD_LOGGING = array(
      // TODO define keys
   );
   const KEYS_MOD_MAIL = array(
      // TODO define keys
   );
   const KEYS_MOD_REQUEST = array(
      // TODO define keys
   );
   const KEYS_MOD_SECURITY = array(
      // TODO define keys
   );
   const KEYS_MOD_SERVICE = array(
      // TODO define keys
   );
   const KEYS_MOD_UI = array(
      // TODO define keys
   );
   const KEYS_MOD_WIZARD = array(
      // TODO define keys
   );

   // Constans: Files to load for every mod type
   const FILES_MODULE_TYPES = array (
      J3Utils::MOD_TYPE_DATABASE => array(
         'J3DB.php'
      )
   );

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
