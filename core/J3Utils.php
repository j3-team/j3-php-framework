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
 */

namespace J3\Core;

class J3Utils {

   // Constans: Directories
   const DIR_CFG             = 'cfg/';
   const DIR_CORE            = 'core/';
   const DIR_DOCS            = 'docs/';
   const DIR_LOGS            = 'logs/';
   const DIR_MODULES         = 'modules/';
   const DIR_MVC             = 'mvc/';
   const DIR_MVC_CONTROLLERS = J3Utils::DIR_MVC . 'controllers/';
   const DIR_MVC_MODELS      = J3Utils::DIR_MVC . 'models/';
   const DIR_MVC_VIEWS       = J3Utils::DIR_MVC . 'views/';
   const DIR_RESOURCES       = 'resources/';

   // Constans: Suffixes
   const SUF_CONTROLLER      = 'Controller';


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
   	exit;
   }

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

}

?>
