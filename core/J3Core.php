<?php
/**
 * J3 PHP Framework - core/Core.php
 *
 * This file contains the main code for load all Framework Core.
 *
 * @author J3 Team
 *
 * @changelog
 *  1. 2017-03-13: Initial version
 *  2. 2017-05-06: Rename class
 */

namespace J3\Core;

require_once('J3Utils.php');

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
   	echo "Hola mundo! $controller";
   }
}

?>
