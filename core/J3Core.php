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
 */

namespace J3\Core;

require_once 'J3Utils.php';

class J3Core {

   static function welcome() {
      echo 'Hello World!... I\'m J3 PHP Framework.';
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

   static function processRequest($controller, $method, $others) {

      // Get Controller class
      if (isset($controller)) {
         $controller = strtolower($controller);

         if (file_exists(J3Utils::DIR_MVC_CONTROLLERS . $controller . ".php")) {

            //instantiate controller object
            require_once(J3Utils::DIR_MVC_CONTROLLERS . $controller . ".php");
            $className = strtoupper($controller[0]) . substr($controller, 1) . J3Utils::SUF_CONTROLLER;
            if (!class_exists($className, false)) {
                echo "Clase $className no definida!";
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
            echo "No se encontro archivo $controller.php";
         }
      } else {
         J3Core::welcome();
      }



   }
}

?>