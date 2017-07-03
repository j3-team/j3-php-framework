<?php
/**
 * J3 PHP Framework - core/modules/J3ModuleLoader.php
 *
 * This file contains the main code for load modules
 *
 * @author J3 Team
 *
 * @changelog
 *  1. 2017-07-03: Initial version
 */

namespace J3\Core\Modules;

use J3\Core\J3Utils;
use J3\Core\Mvc\J3View;

class J3ModuleLoader {

   static function loadExtra($name) {
      if (file_exists(J3Utils::DIR_EXTRAS . $name . '.php')) {
         require_once(J3Utils::DIR_EXTRAS . $name . '.php');
      } else {
         J3View::warning("Extra <strong>$name.php</strong> not found.");
      }
   }

}

?>
