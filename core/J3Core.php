<?php
/**
 * J3 PHP Framework - core/J3Core.php
 *
 * This file contains the main code for load all Framework Core.
 *
 * @author J3 Team
 *
 * @changelog
 *  1. 2017-03-13: Initial version
 *  2. 2017-05-06: Rename class
 *  3. 2017-07-01: Initial implementation of processRequest method
 *  4. 2017-07-03: Change messages language
  *                Require J3Controller
 */

namespace J3\Core;

require_once 'J3Utils.php';
require_once 'mvc/J3View.php';
require_once 'core/modules/J3ModuleLoader.php';
require_once 'core/mvc/J3Controller.php';
require_once 'mvc/controllers/j3base.php';

use J3\Core\Mvc\J3View;

class J3Core {

   static function welcome() {
      J3View::info('Hello World!... I\'m J3 PHP Framework.');
   }

   static function sitemap() {
   	J3Utils::downloadFile('sitemap.xml', 'application/xml');
   }

   static function robots() {
   	J3Utils::downloadFile('robots.txt', 'text/plain');
   }

   static function phpinfo() {
   	echo phpinfo();
   }

   /**
    * This is de main core method.
    * Proceess the request and executes Methods and Views.
    * Params depends of URL: http://<site>/<controller>/<view>/<others>
    * @param $controller Controller name.
    * @param $method Method/View name.
    * @param $others Others parameters.
    */
   static function processRequest($controller, $method, $others) {

      // Get Controller class
      if (isset($controller)) {
         $controller = strtolower($controller);
         if ($controller === J3Utils::DEFAULT_CONTROLLER) {
            J3View::warning("Class <strong>J3BaseController</strong> can be used directly.");
            exit(0);
         }

         if (file_exists(J3Utils::DIR_MVC_CONTROLLERS . $controller . ".php")) {

            //instantiate controller object
            require_once(J3Utils::DIR_MVC_CONTROLLERS . $controller . ".php");
            $className = strtoupper($controller[0]) . substr($controller, 1) . J3Utils::SUF_CONTROLLER;
            if (!class_exists($className, false)) {
                J3View::warning("Class <strong>$className</strong> not defined.");
                return;
            }
            $objController = new $className;

            // evaluate method
            if (isset($method)) {
               $objController->execute($method);
            } else {
               $objController->execute('index');
            }


         } else {
            J3View::warning("File <strong>$controller.php</strong> not found.");
         }
      } else {
         J3Core::welcome();
      }



   }
}

?>
