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
 *  2. 2017-07-20: Load Modules
 */

namespace J3\Core\Modules;

use J3\Core\J3Utils;
use J3\Core\Mvc\J3View;

class J3ModuleLoader {

   /**
    * Loads external library from "extra" folder.
    * @param  String $name PHP File name (without extension).
    * @return void
    */
   static function loadExtra($name) {
      if (file_exists(J3Utils::DIR_EXTRAS . $name . '.php')) {
         require_once(J3Utils::DIR_EXTRAS . $name . '.php');
      } else {
         J3View::warning("Extra <strong>$name.php</strong> not found.");
      }
   }

   /**
    * Loads one module and returns its config.
    * @param  string $type Module type
    * @param  string $mod  Module name
    * @return array        Module settings
    */
   static function loadModule($type, $mod) {
      $path = J3Utils::DIR_MODULES . $type . '/' . $mod;
      if (file_exists($path)) {
         if (file_exists($path . '/' . J3Utils::FILE_INI_MOD)) {
            $ini_array = parse_ini_file($path . '/' . J3Utils::FILE_INI_MOD, true);

            // check ini file keys...
            if (!J3ModuleLoader::check_ini_keys($ini_array[J3Utils::MOD_INI_SECTION_MODULE], strtoupper($type))) {
               return false;
            }

            // require_once for file keys
            foreach ($$ini_array[J3Utils::MOD_INI_SECTION_MODULE] as $key => $value) {
               if (strpos($key, '_file') > 0) {
                  if (file_exists("$path/$value")) {
                     require_once "$path/$value";
                  } else {
                     J3View::warning("File <strong>$path/$value</strong> not found.");
                     return false;
                     // TODO evaluate if use exit instead warning
                  }
               }
            }

            // process specific settings for every module type
            return J3ModuleLoader::load_mod_$type($ini_array);
         } else {
            J3View::warning("File <strong>mod.ini</strong> for module <strong>$type/$mod</strong> not found.");
            return false;
            // TODO evaluate if use exit instead warning
         }
      } else {
         J3View::warning("Module <strong>$type/$mod</strong> not found.");
         return false;
         // TODO evaluate if use exit instead warning
      }
   }


   /**
    * Checks every key put in mod.ini file
    * @param  array  $ini_array mod.ini file keys
    * @param  string $mod_type  Module type (in uppercase)
    * @return boolean           File OK
    */
   static function check_ini_keys($ini_array, $mod_type) {
      $arr_keys = J3Utils::KEYS_MOD_$mod_type;

      foreach ($arr_keys as $key => $required) {
         if ($required === 1) {
            if (!isset($ini_array[$key])) {
               J3View::warning("Required key <strong>$key</strong> not found.");
               // TODO evaluate if use exit instead warning
               return false;
            }
         }
      }

      foreach ($ini_array as $key => $value) {
         if (!isset($arr_keys[$key])) {
            J3View::warning("Key <strong>$key</strong> not reconigzed.");
            // TODO evaluate if use exit instead warning
            return false;
         }
      }

      return true;
   }


   /* FUNCTIONS TO LOAD SPECIFIC MODULE TYPE */

   static function load_mod_database($ini_array) {
      return $ini_array;
   }

}

?>
